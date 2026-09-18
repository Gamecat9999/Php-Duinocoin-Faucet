<?php

final class Validation
{
    public static function sanitizeInput(mixed $input): string
    {
        return trim((string) $input);
    }

    public static function validateWalletAddress(string $walletAddress, string $faucetUsername): bool
    {
        return $walletAddress !== $faucetUsername
            && preg_match('/^[a-zA-Z0-9_]{3,20}$/', $walletAddress) === 1;
    }

    public static function validateCsrf(string $token, string $sessionToken): bool
    {
        return $token !== '' && hash_equals($sessionToken, $token);
    }
}
