# Php-Duinocoin-Faucet
This is a PHP duinocoin faucet developed by Gamecat999. See a live version of the project here https://katfaucet.com. It includes a local CAPTCHA and a blacklist of users who are banned from the faucet for security reasons. For more help join our Discord server: https://discord.gg/HUbHqUQUD2

## Configuration

The server must have PHP cURL and `pdo_sqlite` enabled. Configure these environment variables before serving the faucet:

- `WALLET_USERNAME`
- `WALLET_PASSWORD`
- `DUINOCOIN_API_URL` (optional; defaults to `https://server.duinocoin.com`)

The CAPTCHA is generated and verified locally, so localhost does not need a Google reCAPTCHA key or external CAPTCHA service. On first startup, the application imports the existing files in `data/` into `data/faucet.sqlite`. Keep the SQLite database writable by the PHP process.

POST requests are limited to 10 attempts per IP address every 15 minutes. This protects the faucet from rapid retries while leaving normal page loads unrestricted.


![kf](https://github.com/user-attachments/assets/4d6c4a3b-0a69-4509-82ee-a58d85c03859)
