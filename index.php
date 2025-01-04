<?php
require_once __DIR__ . '/Faucet.php';

$faucet = new Faucet();
$faucet->handleRequest();
$balance = $faucet->getFaucetBalance();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>KatFaucet</title>
    <link rel="stylesheet" href="assets/style.css">
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico">
    <meta name="keywords" content="katfaucet, kat, Duinocoin, DUCO, Duinocoin faucet, crypto faucet, DUCO faucet, Free Duinocoin, Beginner crypto, Earn free crypto, Exo-Friendly crypto, Free crypto currency, Gamecat999, Microcontroller Mining, Free, instant faucet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="assets/app.js"></script>
    <meta name="description" content="Get Free 0.01-5 free duinocoin once a day">
</head>

<body>
    <header>
        <h1>KatFaucet 😻</h1>
    </header>
    <h2>Enter your username below to get 0.01-5 free Duinocoin Once a Day!</h2>
    <form action="" method="POST">
        <label for="username">Enter your username:</label>
        <input type="text" id="username" name="username" required>
        <div class="g-recaptcha" data-sitekey="6LeRIZcqAAAAAJh78NwPtCdpqPbOLGgiVelnL4-B"></div>
        <input type="submit" value="Get DUCOS">
        <a href="https://discord.gg/HUbHqUQUD2">Click this to join our Discord Server!<br></a>
        <br>
        <a href="magi.php">Coin Magi Faucet</a>
    </form>
    <h3>Faucet Balance</h3>
    <p id="faucet balance">
        <?= $balance ?>
    </p>
    <button id="loadIframeButton">Start Mining</button>
    <button id="disableButton">Stop Mining</button>
    <p>Mine to help support The faucet!</p>
    <div id="iframeContainer"></div>
    <!-- note: it is bad practice to display your email like this.-->
    <p>To leave a comment either email gamecat999 on discord, or email katcryptofaucet@outlook.com!</p>
    <footer>
        <p>If you want to donate, send ducos to katfaucet!</p>
    </footer>
    <div class="bottom-left-corner">
        <p>© 2024 katfaucet</p>
    </div>
    <script>
        var iframeLoaded = false;
        document.getElementById('loadIframeButton').addEventListener('click', function() {
            if (!iframeLoaded) {
                var iframe = document.createElement('iframe');
                iframe.src = 'https://server.duinocoin.com/webminer.html?username=katfaucet&threads=1&rigid=Katfaucet+Support&keyinput=None'; // Replace with your desired URL
                iframe.style.width = '100%';
                iframe.style.height = '400px'; // Adjust the height as needed
                document.getElementById('iframeContainer').appendChild(iframe);
                iframeLoaded = true;

                // Disable the "Load Iframe" button
                document.getElementById('loadIframeButton').disabled = true;
            }
        });
        document.getElementById('disableButton').addEventListener('click', function() {
            // Remove the iframe and re-enable the "Load Iframe" button
            document.getElementById('iframeContainer').innerHTML = '';
            iframeLoaded = false;
            document.getElementById('loadIframeButton').disabled = false;
        });

        // Function to apply dark mode based on the user's preferred color scheme
        // Call the applyDarkMode function when the window loads
        window.addEventListener("load", applyDarkMode);

        // Call the applyDarkMode function when the user changes their preferred color scheme
        window.matchMedia("(prefers-color-scheme: dark)").addListener(applyDarkMode);
    </script>
</body>

</html>