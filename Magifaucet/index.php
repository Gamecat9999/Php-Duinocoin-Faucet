<html>
<body>
<title>KatFaucet</title>
<style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f7f7f7;
    color: #333;
    text-align: center;
}

body.dark-mode {
    background-color: #1c1c1c;
    color: #f1f1f1;
}

header {
    background-color: #6AC2EF;
    padding: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

header h1 {
    margin: 0;
    font-size: 2.5rem;
    color: #333;
}

body.dark-mode header {
    background-color: #6AC2EF;
}

form {
    background-color: #fff;
    padding: 20px;
    margin: 20px auto;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

body.dark-mode form {
    background-color: #2e2e2e;
}

form label {
    display: block;
    font-size: 1rem;
    margin-bottom: 10px;
    text-align: left;
}

form input[type="text"],
form input[type="submit"] {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
}

form input[type="submit"] {
    background-color: #ffcc00;
    color: #333;
    border: none;
    cursor: pointer;
    font-weight: bold;
}

form input[type="submit"]:hover {
    background-color: #ffaa00;
}

body.dark-mode form input[type="text"],
body.dark-mode form input[type="submit"] {
    background-color: #444;
    color: white;
}

a {
    color: #6AC2EF;
    font-weight: bold;
    text-decoration: none;
}

body.dark-mode a {
    color: #6AC2EF;
}

img {
    width: 100%;
    max-width: 400px;
    margin: 10px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

footer {
    background-color: #6AC2EF;
    padding: 10px;
    color: #333;
    position: fixed;
    width: 100%;
    bottom: 0;
    left: 0;
}

body.dark-mode footer {
    background-color: #6AC2EF;
    color: #fff;
}
</style>
<script>
// Function to apply dark mode based on the user's preferred color scheme
function applyDarkMode() {
    var body = document.body;
    var prefersDarkMode = window.matchMedia("(prefers-color-scheme: dark)").matches;

    if (prefersDarkMode) {
        body.classList.add("dark-mode");
    } else {
        body.classList.remove("dark-mode");
    }
}

// Call the applyDarkMode function when the window loads
window.addEventListener("load", applyDarkMode);

// Call the applyDarkMode function when the user changes their preferred color scheme
window.matchMedia("(prefers-color-scheme: dark)").addListener(applyDarkMode);
</script>

<header>
    <h1>KatFaucet 😻</h1>
</header>
<head>
<meta name="keywords" content="katfaucet, kat, Duinocoin, DUCO, Duinocoin faucet, crypto faucet, DUCO faucet, Free Duinocoin, Beginner crypto, Earn free crypto, Exo-Friendly crypto, Free crypto currency, Gamecat999, Microcontroller Mining, Free, instant faucet, Coin magi, Magi, Magi faucet, Free Magi, Free XMG, free XMG magi faucet,">
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<meta name="description" content="Get Free 0.01-5 free duinocoin once a day">

</head>
<h2>Enter your Magi username or address to get 0.01 Magi A day!</h2>



<form action="" method="post" onsubmit="return validateForm()">
    <label for="username">Enter your username:</label>
    <input type="text" id="username" name="username" required>
    <div class="g-recaptcha" data-sitekey="6LeFe3UqAAAAAPl_CkHLKNPYOFj-yUWgg-MnjI-t"></div>
    <input type="submit" value="Get Magi">
</form>
<script>
function validateForm() {
    var response = grecaptcha.getResponse();
    if (!response) {
        alert('Dont be a Evil Bot. Please complete the Captcha.');
        return false;
    }
    return true;
}
</script>

<a href="https://discord.gg/HUbHqUQUD2">Click this to join our Discord Server!<br></a>
<br>
<a href="index.php">Duinocoin faucet</a>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h2>Faucet balance</h2>
    <p id="balance">
        <?php
            $url = 'https://magi.duinocoin.com/balances/katfaucet';
            $json = @file_get_contents($url);
            if ($json === FALSE) {
                echo 'Error fetching balance';
            } else {
                $data = json_decode($json, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    echo 'Error decoding JSON: ' . json_last_error_msg();
                } else {
                    if (isset($data['result']['balance']['balance'])) {
                        $balance = $data['result']['balance']['balance'];
                        echo "Balance: $balance XMG";
                    } else {
                        echo 'Error: Balance not found in the response';
                    }
                }
            }
        ?>
    </p>
</body>
</html>

<a href="http://magi.duinocoin.com">Get started with Coin Magi!</a>

   
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Click Limiter</title>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            let lastClickTime = 0;

            document.addEventListener('click', (e) => {
                const currentTime = new Date().getTime();
                if (currentTime - lastClickTime < 1000) {
                    e.stopImmediatePropagation();
                    e.preventDefault();
                } else {
                    lastClickTime = currentTime;
                }
            }, true);
        });
    </script>
</head>

</html>



</body>
</html>
<?php
// File path to store the cooldown data
$cooldownFile = 'cooldown.txt';
$blacklistFile = 'blacklist.txt';
// Initialize the cooldown data array
$cooldownData = array();
$cooldownTime = 86400; // 1 day in seconds
$amount = 0.015;
// Read the cooldown data from the file if it exists
if (file_exists($cooldownFile)) {
    $cooldownData = json_decode(file_get_contents($cooldownFile), true);
} else {
    // Create the cooldown file if it doesn't exist
    file_put_contents($cooldownFile, json_encode($cooldownData));
}

$blacklistData = array();
// Read the blacklist data from the file if it exists
if (file_exists($blacklistFile)) {
    $blacklistData = file($blacklistFile, FILE_IGNORE_NEW_LINES);
} else {
    // Create the blacklist file if it doesn't exist
    file_put_contents($blacklistFile, implode("\n", $blacklistData));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = "katfaucet";
    $recipient = $_POST["username"];
    $password = "Stayout1";
    $memo = "KatFaucet Reward";

    $currentTime = time(); // Get the current time
    $ipAddress = $_SERVER['REMOTE_ADDR']; // Get the IP address of the user

    // Check if the cooldown data array is not null
    if ($cooldownData !== null) {
        // Check if the recipient's username is in the cooldown list
        if (array_key_exists($recipient, $cooldownData) && $currentTime - $cooldownData[$recipient] < $cooldownTime) {
            $remainingTime = $cooldownTime - ($currentTime - $cooldownData[$recipient]);

            // Calculate hours, minutes, and seconds
            $hours = floor($remainingTime / 3600);
            $minutes = floor(($remainingTime % 3600) / 60);
            $seconds = $remainingTime % 60;

            // Format the output string
            // Format the output string with words "hours", "minutes", and "seconds"
            $formattedTime = $hours . " hours, " . $minutes . " minutes, and " . $seconds . " seconds";
            

            echo "Be more Patient. Wait for " . $formattedTime . " before Trying again. Find a Kat to pet while you wait.";
            exit;
        }
    }

    // Check if the recipient's username is in the blacklist
    if (in_array($recipient, $blacklistData)) {
        echo "Sorry, You are not allowed to use this faucet. Go pet a kat instead.";
        exit;
    }

    // Your existing code for sending transaction request...

    $url = "https://magi.duinocoin.com/transaction/?username=$username&password=$password&amount=$amount&recipient=$recipient&memo=$memo";

    $response = file_get_contents($url);

    if ($response === false) {
        echo "Error: Failed to send transaction request.";
    } else {
        $transactionData = json_decode($response, true);

        if (isset($transactionData['success']) && $transactionData['success']) {
            echo "Transaction successful. Sent ". $amount . " to " . $recipient . ". Transaction id ", 

            // Update the last submit time in the cooldown data for the recipient's username
            $cooldownData[$recipient] = $currentTime;
            // Save the updated cooldown data to the file
            file_put_contents($cooldownFile, json_encode($cooldownData));

        } else {
            echo "Error: Transaction failed. Reason: " . (isset($transactionData['error']) ? $transactionData['error'] : 'Unknown. Report this to kat on discord pls!');
        }
    }
}
?>



</body>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<footer>
    <p>If you want to donate, send Magi to katfaucet!</p>
</footer>
<style>
.bottom-left-corner {
    position: fixed;
    bottom: 20px;
    left: 20px;
    background-color: #f7f7f7;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

body.dark-mode .bottom-left-corner {
    background-color: #2e2e2e;
    color: #f1f1f1;
}
</style>

<div class="bottom-left-corner">
    <p>© 2024 katfaucet</p>
</div>
</body>
</html>