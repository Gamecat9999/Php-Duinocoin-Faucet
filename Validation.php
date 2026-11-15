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

    public static function getClientIp(array $server, string $trustedProxyIp = ''): string
    {
        $remoteAddress = $server['REMOTE_ADDR'] ?? '';
        if (!is_string($remoteAddress)) {
            return 'unknown';
        }

        if ($trustedProxyIp !== '' && $remoteAddress === $trustedProxyIp) {
            $cloudflareAddress = $server['HTTP_CF_CONNECTING_IP'] ?? '';
            if (is_string($cloudflareAddress) && filter_var($cloudflareAddress, FILTER_VALIDATE_IP) !== false) {
                return $cloudflareAddress;
            }
        }

        return filter_var($remoteAddress, FILTER_VALIDATE_IP) !== false ? $remoteAddress : 'unknown';
    }
}
