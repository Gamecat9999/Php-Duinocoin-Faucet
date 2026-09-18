<?php

final class Database
{
    private PDO $connection;

    public function __construct(string $path)
    {
        $this->connection = new PDO('sqlite:' . $path, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $this->connection->exec('PRAGMA busy_timeout = 5000');
        $this->initialize($path);
    }

    public function isBlacklisted(string $walletAddress): bool
    {
        $statement = $this->connection->prepare('SELECT 1 FROM blacklist WHERE wallet_address = :wallet_address LIMIT 1');
        $statement->execute(['wallet_address' => $walletAddress]);
        return $statement->fetchColumn() !== false;
    }

    public function reserveClaim(string $walletAddress, int $currentTime, int $cooldown): ?string
    {
        $this->connection->exec('BEGIN IMMEDIATE');
        try {
            if ($this->isBlacklisted($walletAddress)) {
                $this->connection->commit();
                return 'Sorry, You are not allowed to use this faucet. Go pet a kat instead.';
            }

            $statement = $this->connection->prepare('SELECT claimed_at FROM cooldowns WHERE wallet_address = :wallet_address');
            $statement->execute(['wallet_address' => $walletAddress]);
            $claimedAt = $statement->fetchColumn();
            if ($claimedAt !== false && $currentTime - (int) $claimedAt < $cooldown) {
                $remainingTime = $cooldown - ($currentTime - (int) $claimedAt);
                $hours = intdiv($remainingTime, 3600);
                $minutes = intdiv($remainingTime % 3600, 60);
                $seconds = $remainingTime % 60;
                $this->connection->commit();
                return "Be more Patient. Wait for $hours hours, $minutes minutes, and $seconds seconds before trying again. Find a Kat to pet while you wait.";
            }

            $statement = $this->connection->prepare(
                'INSERT INTO cooldowns (wallet_address, claimed_at) VALUES (:wallet_address, :claimed_at)
                 ON CONFLICT(wallet_address) DO UPDATE SET claimed_at = excluded.claimed_at'
            );
            $statement->execute([
                'wallet_address' => $walletAddress,
                'claimed_at' => $currentTime,
            ]);
            $this->connection->commit();
            return null;
        } catch (Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }

    public function releaseClaim(string $walletAddress, int $claimTime): void
    {
        $statement = $this->connection->prepare('DELETE FROM cooldowns WHERE wallet_address = :wallet_address AND claimed_at = :claimed_at');
        $statement->execute([
            'wallet_address' => $walletAddress,
            'claimed_at' => $claimTime,
        ]);
    }

    public function recordClaim(string $walletAddress, int $claimedAt, float $amount): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO claims (wallet_address, claimed_at, amount)
             VALUES (:wallet_address, :claimed_at, :amount)'
        );
        $statement->execute([
            'wallet_address' => $walletAddress,
            'claimed_at' => $claimedAt,
            'amount' => $amount,
        ]);
    }

    public function getRecentClaims(int $limit = 8): array
    {
        $limit = max(1, min($limit, 50));
        return $this->connection
            ->query("SELECT wallet_address, claimed_at, amount FROM claims ORDER BY claimed_at DESC, id DESC LIMIT $limit")
            ->fetchAll();
    }

    public function consumeRateLimit(string $ipAddress, int $limit, int $window): ?int
    {
        $currentTime = time();
        $this->connection->exec('BEGIN IMMEDIATE');
        try {
            $statement = $this->connection->prepare('SELECT window_started, request_count FROM rate_limits WHERE ip_address = :ip_address');
            $statement->execute(['ip_address' => $ipAddress]);
            $record = $statement->fetch();

            if ($record === false || $currentTime - (int) $record['window_started'] >= $window) {
                $statement = $this->connection->prepare(
                    'INSERT INTO rate_limits (ip_address, window_started, request_count)
                     VALUES (:ip_address, :window_started, 1)
                     ON CONFLICT(ip_address) DO UPDATE SET
                        window_started = excluded.window_started,
                        request_count = excluded.request_count'
                );
                $statement->execute([
                    'ip_address' => $ipAddress,
                    'window_started' => $currentTime,
                ]);
                $this->connection->commit();
                return null;
            }

            if ((int) $record['request_count'] >= $limit) {
                $this->connection->commit();
                return max(1, $window - ($currentTime - (int) $record['window_started']));
            }

            $statement = $this->connection->prepare(
                'UPDATE rate_limits SET request_count = request_count + 1 WHERE ip_address = :ip_address'
            );
            $statement->execute(['ip_address' => $ipAddress]);
            $this->connection->commit();
            return null;
        } catch (Throwable $exception) {
            $this->connection->rollBack();
            throw $exception;
        }
    }

    private function initialize(string $path): void
    {
        $this->connection->exec(
            'CREATE TABLE IF NOT EXISTS cooldowns (
                wallet_address TEXT PRIMARY KEY,
                claimed_at INTEGER NOT NULL
            );
            CREATE TABLE IF NOT EXISTS blacklist (
                wallet_address TEXT PRIMARY KEY
            );
            CREATE TABLE IF NOT EXISTS claims (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                wallet_address TEXT NOT NULL,
                claimed_at INTEGER NOT NULL,
                amount REAL NOT NULL
            );
            CREATE TABLE IF NOT EXISTS rate_limits (
                ip_address TEXT PRIMARY KEY,
                window_started INTEGER NOT NULL,
                request_count INTEGER NOT NULL
            );'
        );

        $this->importLegacyCooldowns(dirname($path) . '/cooldown.txt');
        $this->importLegacyBlacklist(dirname($path) . '/blacklist.txt');
    }

    private function importLegacyCooldowns(string $path): void
    {
        if ((int) $this->connection->query('SELECT COUNT(*) FROM cooldowns')->fetchColumn() > 0 || !is_readable($path)) {
            return;
        }

        $data = json_decode((string) file_get_contents($path), true);
        if (!is_array($data)) {
            return;
        }

        $statement = $this->connection->prepare('INSERT OR IGNORE INTO cooldowns (wallet_address, claimed_at) VALUES (:wallet_address, :claimed_at)');
        foreach ($data as $walletAddress => $claimedAt) {
            if (is_string($walletAddress) && is_numeric($claimedAt)) {
                $statement->execute([
                    'wallet_address' => $walletAddress,
                    'claimed_at' => (int) $claimedAt,
                ]);
            }
        }
    }

    private function importLegacyBlacklist(string $path): void
    {
        if ((int) $this->connection->query('SELECT COUNT(*) FROM blacklist')->fetchColumn() > 0 || !is_readable($path)) {
            return;
        }

        $statement = $this->connection->prepare('INSERT OR IGNORE INTO blacklist (wallet_address) VALUES (:wallet_address)');
        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $walletAddress) {
            $statement->execute(['wallet_address' => trim($walletAddress)]);
        }
    }
}
