<?php
session_start();
require_once __DIR__ . '/Faucet.php';

try {
    $faucet = new Faucet();
    $message = $faucet->handleRequest();
    $balance = $faucet->getFaucetBalance();
    $csrf = $faucet->getCSRF();
    $captchaQuestion = $faucet->getCaptchaQuestion();
    $recentClaims = $faucet->getRecentClaims();
} catch (Throwable $exception) {
    error_log($exception->getMessage());
    http_response_code(503);
    $message = 'The faucet is temporarily unavailable. Please contact the administrator.';
    $balance = 0.0;
    $csrf = '';
    $captchaQuestion = 'Unavailable';
    $recentClaims = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>KatFaucet</title>
    <meta name="color-scheme" content="light dark">
    <script>
        document.documentElement.classList.toggle('dark-mode', window.matchMedia('(prefers-color-scheme: dark)').matches);
    </script>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <meta name="keywords" content="katfaucet, kat, Duinocoin, DUCO, Duinocoin faucet, crypto faucet, DUCO faucet, Free Duinocoin, Beginner crypto, Earn free crypto, Exo-Friendly crypto, Free crypto currency, Gamecat999, Microcontroller Mining, Free, instant faucet">
    <script src="assets/app.js"></script>
    <meta name="description" content="Claim 1 DUCO once a day from KatFaucet">
</head>

<body>
    <div class="page-shell">
        <header class="site-header">
            <a class="brand" href="/" aria-label="KatFaucet home">
                <span class="brand-mark">K</span>
                <span>KatFaucet</span>
            </a>
            <span class="header-note">DUCO community faucet</span>
        </header>

        <main class="content-grid">
            <section class="claim-panel" aria-labelledby="claim-title">
                <p class="eyebrow">Daily drop <span aria-hidden="true">/</span> 1 DUCO</p>
                <h1 id="claim-title">A little crypto,<br><em>right on time.</em></h1>
                <p class="intro">Enter your DuinoCoin username below. You can claim once every 24 hours.</p>

                <form action="" method="POST">
                    <input type="hidden" name="csrf_token" value="<?=htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8')?>" required>
                    <div class="field-group">
                        <label for="username">DuinoCoin username</label>
                        <input type="text" id="username" name="username" placeholder="your_username" autocomplete="username" required>
                    </div>
                    <div class="field-group">
                        <label for="captcha_answer">Quick check <span class="field-hint"><?=htmlspecialchars($captchaQuestion, ENT_QUOTES, 'UTF-8')?></span></label>
                        <input type="text" id="captcha_answer" name="captcha_answer" inputmode="numeric" autocomplete="off" placeholder="Answer" required>
                    </div>
                    <button type="submit">Claim DUCO <span aria-hidden="true">→</span></button>
                </form>

                <?php if ($message !== null): ?>
                    <p class="message" role="status"><?=htmlspecialchars($message, ENT_QUOTES, 'UTF-8')?></p>
                <?php endif; ?>
            </section>

            <aside class="side-panel" aria-label="Faucet information">
                <div class="balance-card">
                    <div class="card-label"><span class="status-dot"></span> Faucet balance</div>
                    <p id="faucet-balance"><?=htmlspecialchars(number_format($balance, 0), ENT_QUOTES, 'UTF-8')?> <span>DUCO</span></p>
                    <p class="card-caption">Available for the community</p>
                </div>
                <div class="info-block">
                    <p class="eyebrow">Good to know</p>
                    <h2>One claim per wallet, every day.</h2>
                    <p>Your reward is sent directly to your DuinoCoin wallet after the username is verified.</p>
                </div>
                <a class="discord-link" href="https://discord.gg/HUbHqUQUD2">
                    <span class="discord-icon" aria-hidden="true">✦</span>
                    <span><strong>Need a hand?</strong><small>Join the KatFaucet Discord</small></span>
                    <span aria-hidden="true">↗</span>
                </a>
                <section class="claim-log" aria-labelledby="claim-log-title">
                    <div class="log-heading">
                        <p class="eyebrow" id="claim-log-title">Recent claims</p>
                        <span>Latest 8</span>
                    </div>
                    <?php if ($recentClaims === []): ?>
                        <p class="empty-log">Successful claims will appear here.</p>
                    <?php else: ?>
                        <ul>
                            <?php foreach ($recentClaims as $claim): ?>
                                <li>
                                    <span><strong><?=htmlspecialchars((string) $claim['wallet_address'], ENT_QUOTES, 'UTF-8')?></strong><small><?=htmlspecialchars(date('M j, Y g:i A', (int) $claim['claimed_at']), ENT_QUOTES, 'UTF-8')?></small></span>
                                    <b>+1 DUCO</b>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </section>
            </aside>
        </main>

        <footer>
            <span>© 2026 KatFaucet</span>
            <span>Send donations to <strong>katfaucet</strong></span>
        </footer>
    </div>
</body>

</html>