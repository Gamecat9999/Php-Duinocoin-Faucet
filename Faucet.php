<?php

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/DuinoCoinApi.php';
require_once __DIR__ . '/LocalCaptcha.php';
require_once __DIR__ . '/Validation.php';

final class Faucet
{
    private const COOLDOWN_TIME = 86400;
    private const RATE_LIMIT = 10;
    private const RATE_WINDOW = 900;

    private string $walletUsername;
    private string $walletPassword;
    private Database $database;
    private DuinoCoinApi $api;
    private LocalCaptcha $captcha;
    private string $csrfToken;

    public function __construct()
    {
        $this->walletUsername = getenv('WALLET_USERNAME') ?: ($_ENV['wallet_username'] ?? 'katfaucet');
        $this->walletPassword = getenv('WALLET_PASSWORD') ?: ($_ENV['wallet_password'] ?? '');
        $this->database = new Database(__DIR__ . '/data/faucet.sqlite');
        $this->api = new DuinoCoinApi(getenv('DUINOCOIN_API_URL') ?: 'https://server.duinocoin.com');
        $this->captcha = new LocalCaptcha();
        $this->csrfToken = $this->setToken();
    }

    public function getCSRF(): string
    {
        return $this->csrfToken;
    }

    public function handleRequest(): ?string
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            return null;
        }

        $recipient = Validation::sanitizeInput($_POST['username'] ?? '');
        $csrfToken = Validation::sanitizeInput($_POST['csrf_token'] ?? '');
        $captchaAnswer = Validation::sanitizeInput($_POST['captcha_answer'] ?? '');
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        try {
            $retryAfter = $this->database->consumeRateLimit($ipAddress, self::RATE_LIMIT, self::RATE_WINDOW);
            if ($retryAfter !== null) {
                $minutes = intdiv($retryAfter, 60);
                $seconds = $retryAfter % 60;
                return "Too many attempts. Try again in $minutes minutes and $seconds seconds.";
            }
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return 'The faucet is temporarily unavailable. Please try again later.';
        }

        if (!Validation::validateCsrf($csrfToken, $this->csrfToken)) {
            return 'Invalid CSRF token.';
        }
        if (!Validation::validateWalletAddress($recipient, $this->walletUsername)) {
            return 'Invalid wallet address.';
        }
        if (!$this->captcha->verify($captchaAnswer)) {
            return 'Incorrect or expired CAPTCHA answer.';
        }

        try {
            if (!$this->api->walletExists($recipient)) {
                return "Wallet doesn't exist.";
            }

            $currentTime = time();
            $claimError = $this->database->reserveClaim($recipient, $currentTime, self::COOLDOWN_TIME);
            if ($claimError !== null) {
                return $claimError;
            }

            $amount = 1.0;
            $transaction = $this->api->sendTransaction(
                $this->walletUsername,
                $this->walletPassword,
                $recipient,
                $amount,
                'KatFaucet'
            );
            if (($transaction['success'] ?? false) !== true) {
                $this->database->releaseClaim($recipient, $currentTime);
                $reason = $transaction['message'] ?? $transaction['error'] ?? 'Please try again later.';
                return 'Error: Transaction failed. ' . htmlspecialchars((string) $reason, ENT_QUOTES, 'UTF-8');
            }

            try {
                $this->database->recordClaim($recipient, $currentTime, $amount);
            } catch (Throwable $exception) {
                error_log($exception->getMessage());
            }

            return "Transaction successful. Sent $amount to " . htmlspecialchars($recipient, ENT_QUOTES, 'UTF-8') . '.';
        } catch (Throwable $exception) {
            if (isset($currentTime, $recipient)) {
                $this->database->releaseClaim($recipient, $currentTime);
            }
            error_log($exception->getMessage());
            return 'The faucet is temporarily unavailable. Please try again later.';
        }
    }

    public function getFaucetBalance(): float
    {
        try {
            return $this->api->faucetBalance($this->walletUsername);
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return 0.0;
        }
    }

    public function getCaptchaQuestion(): string
    {
        return $this->captcha->getQuestion();
    }

    public function getRecentClaims(): array
    {
        try {
            return $this->database->getRecentClaims();
        } catch (Throwable $exception) {
            error_log($exception->getMessage());
            return [];
        }
    }

    private function setToken(): string
    {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
