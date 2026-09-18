# KatFaucet

KatFaucet is a PHP DuinoCoin faucet that sends a fixed 1 DUCO reward to a verified wallet once every 24 hours.

Live site: https://katfaucet.com
Discord: https://discord.gg/HUbHqUQUD2

## Features

- Fixed 1 DUCO payout per successful claim
- SQLite-backed cooldowns, blacklist, rate limits, and claim history
- Local arithmetic CAPTCHA with five-minute expiry
- CSRF protection and strict wallet-name validation
- Ten POST attempts per IP address every 15 minutes
- Whole-number faucet balance display
- Responsive KatFaucet interface with light and dark themes
- Automatic import of existing cooldown and blacklist text files

## Requirements

- PHP 8.0 or newer
- PHP cURL extension
- PHP `pdo_sqlite` extension
- A writable `data/` directory
- A DuinoCoin faucet wallet

## Configuration

Set these environment variables before starting the application:

- `WALLET_USERNAME`: the faucet wallet username
- `WALLET_PASSWORD`: the faucet wallet password
- `DUINOCOIN_API_URL`: optional API base URL; defaults to `https://server.duinocoin.com`

Do not commit credentials or place them in PHP source files. The local CAPTCHA does not require Google reCAPTCHA credentials.

## Local Setup

From the project directory in PowerShell:

```powershell
$env:WALLET_USERNAME = "katfaucet"
$env:WALLET_PASSWORD = "your_wallet_password"
php -S 127.0.0.1:8088 -t .
```

Open http://127.0.0.1:8088/ in a browser.

The first request creates `data/faucet.sqlite` and imports existing state from:

- `data/cooldown.txt`
- `data/blacklist.txt`

The SQLite database must remain writable by the PHP process.

## Data and Security

Successful claims are recorded in the SQLite `claims` table and displayed as a recent public history. Usernames are escaped before rendering. Wallet input is restricted to letters, numbers, and underscores before it reaches the API.

The DuinoCoin transaction endpoint requires encoded GET parameters. Use HTTPS in production and avoid logging transaction URLs because they contain API credentials.

The built-in PHP server is intended for local development only. Use a production web server with HTTPS, secure environment configuration, backups, and additional monitoring for a public deployment.
