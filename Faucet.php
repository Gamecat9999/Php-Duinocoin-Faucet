<?php

//note: you should think about migrating the .txt files to a SQLite database.
//This approach would be better for security.

class Faucet
{
    private $apiUrl = "https://server.duinocoin.com/";
    
    private $cooldownFile = 'cooldown.txt';
    private $blacklistFile = 'blacklist.txt';
    private $cooldownData = [];
    private $blacklistData = [];
    private $cooldownTime = 86400; // 24 hours in seconds
    private $wallet_username;
    private $wallet_password;
    private $memo = 'KatFaucet';

    public function __construct()
    {
        //set the environment variables.
        $this->wallet_username = $_ENV['wallet_username'] ?? "tbwcjw";
        $this->wallet_password = $_ENV['wallet_password'] ?? "";

        $this->loadCooldownData();
        $this->loadBlacklistData();
    }

    private function loadCooldownData(): void
    {
        if (file_exists(filename: $this->cooldownFile)) {
            $file = fopen(filename: $this->cooldownFile, mode: 'r');
            if ($file) {
                $contents = fread(stream: $file, length: filesize(filename: $this->cooldownFile));
                fclose(stream: $file);
                $this->cooldownData = json_decode(json: $contents, associative: true);
            }
        } else {
            $file = fopen(filename: $this->cooldownFile, mode: 'w');
            if ($file) {
                fwrite(stream: $file, data: json_encode(value: $this->cooldownData));
                fclose(stream: $file);
            }
        }
    }
    private function loadBlacklistData(): void
    {
        if (file_exists(filename: $this->blacklistFile)) {
            $file = fopen(filename: $this->blacklistFile, mode: 'r');
            if ($file) {
                $contents = fread(stream: $file, length: filesize(filename: $this->blacklistFile));
                fclose(stream: $file);
                $this->blacklistData = explode(separator: "\n", string: $contents);
            }
        } else {
            $file = fopen(filename: $this->blacklistFile, mode: 'w');
            if ($file) {
                fwrite(stream: $file, data: implode(separator: "\n", array: $this->blacklistData));
                fclose(stream: $file);
            }
        }
    }
    public function sanitizeInput($input): mixed {
        return htmlspecialchars(string: trim(string: $input), flags: ENT_QUOTES, encoding: 'UTF-8');
    }
    public function validateWalletAddress($wallet_address): bool|int {
        if(strlen(string: $wallet_address) < 1) return false;
        if($wallet_address == $this->wallet_username) return false;
        if($wallet_address === null) return false;
        return preg_match(pattern: '/^[a-zA-Z0-9_]{3,20}$/', subject: $wallet_address);
    }
    public function checkWalletExists(string $recipient): bool {
        $url = $this->apiUrl . '/balances/' . $recipient;
        $curl = curl_init(url: $url);
        curl_setopt(handle: $curl, option: CURLOPT_RETURNTRANSFER, value: true);
        curl_setopt(handle: $curl, option: CURLOPT_SSL_VERIFYPEER, value: true);
        curl_setopt(handle: $curl, option: CURLOPT_SSL_VERIFYHOST, value: 2);
        curl_setopt(handle: $curl, option: CURLOPT_HTTPHEADER, value: [
            'Content-Type: application/json',
        ]);

        $response = curl_exec(handle: $curl);
        curl_close(handle: $curl);
        $result = json_decode(json: $response, associative: true);
        
        if(isset($result['success']) && $result['success'] === true) {
            return true;
        }
        return false;
    }

    public function handleRequest(): void
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $recipient = $this->sanitizeInput(input: $_POST["username"]);

            //you have this defined but never used.
            $ipAddress = $_SERVER['REMOTE_ADDR'];
            
            //validate wallet address
            if(!$this->validateWalletAddress(wallet_address: $recipient)) {
                echo "Invalid wallet address";
                exit;
            }
            //check wallet exists
            if(!$this->checkWalletExists(recipient: $recipient)) {
                echo "Wallet doesn't exist.";
                exit;
            }
            $currentTime = time();
            if ($this->isCooldown(recipient: $recipient, currentTime: $currentTime)) {
                echo $this->getCooldownMessage(recipient: $recipient, currentTime: $currentTime);
            }

            if ($this->isBlacklisted(recipient: $recipient)) {
                echo "Sorry, You are not allowed to use this faucet. Go pet a kat instead.";
            }

            $amount = mt_rand(min: 1, max: 500) / 100;
            $this->sendTransactionRequest(recipient: $recipient, amount: $amount, currentTime: $currentTime);
        }
    }

    private function isCooldown($recipient, $currentTime): bool
    {
        return isset($this->cooldownData[$recipient]) &&
               $currentTime - $this->cooldownData[$recipient] < $this->cooldownTime;
    }

    private function getCooldownMessage($recipient, $currentTime): string
    {
        $remainingTime = $this->cooldownTime - ($currentTime - $this->cooldownData[$recipient]);
        $hours = floor(num: $remainingTime / 3600);
        $minutes = floor(num: ($remainingTime % 3600) / 60);
        $seconds = $remainingTime % 60;
        return "Be more Patient. Wait for $hours hours, $minutes minutes, and $seconds seconds before trying again. Find a Kat to pet while you wait.";
    }

    private function isBlacklisted($recipient): bool
    {
        return in_array(needle: $recipient, haystack: $this->blacklistData);
    }

    private function sendTransactionRequest($recipient, $amount, $currentTime): void
    {
        $params = [
            'username'  => $this->wallet_username,
            'password'  => $this->wallet_password,
            'recipient' => $recipient,
            'amount'    => $amount,
            'memo'      => $this->memo,
        ];

        $url = $this->apiUrl . 'transaction/?' . http_build_query(data: $params);

        $ch = curl_init();
        curl_setopt(handle: $ch, option: CURLOPT_URL, value: $url);
        curl_setopt(handle: $ch, option: CURLOPT_RETURNTRANSFER, value: true);
        curl_setopt(handle: $ch, option: CURLOPT_TIMEOUT, value: 30);
        $response = curl_exec(handle: $ch);
        curl_close(handle: $ch);

        if ($response === false) {
            echo "Error: Failed to send transaction request.";
            return;
        }

        $transactionData = json_decode(json: $response, associative: true);

        if (isset($transactionData['success']) && $transactionData['success']) {
            echo "Transaction successful. Sent $amount to $recipient";
        
            $this->cooldownData[$recipient] = $currentTime;
            
            $file = fopen(filename: $this->cooldownFile, mode: 'w');
            if ($file) {
                fwrite(stream: $file, data: json_encode(value: $this->cooldownData));
                fclose(stream: $file);
            } else {
                echo "Error: Could not open cooldown file for writing.";
            }
        } else {
            echo "Error: Transaction failed. Reason: " . (isset($transactionData['error']) ? $transactionData['error'] : 'Unknown. Report this to kat on discord pls!');
        }
    }

    public function getFaucetBalance(): float {
        $url = $this->apiUrl . 'balances/katfaucet';
        
        $curl = curl_init(url: $url);
        curl_setopt(handle: $curl, option: CURLOPT_RETURNTRANSFER, value: true);
        curl_setopt(handle: $curl, option: CURLOPT_SSL_VERIFYPEER, value: true);
        curl_setopt(handle: $curl, option: CURLOPT_SSL_VERIFYHOST, value: 2);
        curl_setopt(handle: $curl, option: CURLOPT_HTTPHEADER, value: [
            'Content-Type: application/json',
        ]);

        $response = curl_exec(handle: $curl);
        curl_close(handle: $curl);

        $result = json_decode(json: $response, associative: true);
        
        return $result['result']['balance'];
    }
}

?>
