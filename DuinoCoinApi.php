<?php

final class DuinoCoinApi
{
    public function __construct(private string $baseUrl = 'https://server.duinocoin.com')
    {
    }

    public function walletExists(string $walletAddress): bool
    {
        $result = $this->request('/balances/' . rawurlencode($walletAddress));
        return ($result['success'] ?? false) === true;
    }

    public function sendTransaction(string $username, string $password, string $recipient, float $amount, string $memo): array
    {
        return $this->request('/transaction/?' . http_build_query([
            'username' => $username,
            'password' => $password,
            'recipient' => $recipient,
            'amount' => $amount,
            'memo' => $memo,
        ], '', '&', PHP_QUERY_RFC3986));
    }

    public function faucetBalance(string $walletAddress): float
    {
        $result = $this->request('/balances/' . rawurlencode($walletAddress));
        return is_numeric($result['result']['balance'] ?? null) ? (float) $result['result']['balance'] : 0.0;
    }

    private function request(string $path, ?array $postData = null): array
    {
        $curl = curl_init(rtrim($this->baseUrl, '/') . $path);
        if ($curl === false) {
            throw new RuntimeException('Could not initialize the DuinoCoin API client.');
        }

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);
        $caBundle = getenv('CURL_CA_BUNDLE') ?: ini_get('curl.cainfo');
        if (is_string($caBundle) && is_file($caBundle)) {
            curl_setopt($curl, CURLOPT_CAINFO, $caBundle);
        }
        if ($postData !== null) {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData, '', '&', PHP_QUERY_RFC3986));
        }

        $response = curl_exec($curl);
        $statusCode = (int) curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $error = curl_error($curl);

        if ($response === false || $error !== '') {
            throw new RuntimeException('The DuinoCoin API could not be reached.');
        }
        if ($statusCode < 200 || $statusCode >= 300) {
            throw new RuntimeException('The DuinoCoin API returned an unexpected response.');
        }

        $result = json_decode($response, true);
        if (!is_array($result)) {
            throw new RuntimeException('The DuinoCoin API returned invalid data.');
        }
        return $result;
    }
}
