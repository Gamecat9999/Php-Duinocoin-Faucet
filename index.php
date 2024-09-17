<html>
<body>
<title>KatFaucet🐱</title>
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
    background-color: #ffcc00;
    padding: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

header h1 {
    margin: 0;
    font-size: 2.5rem;
    color: #333;
}

body.dark-mode header {
    background-color: #ffb300;
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
    color: #ffcc00;
    font-weight: bold;
    text-decoration: none;
}

body.dark-mode a {
    color: #ff9900;
}

img {
    width: 100%;
    max-width: 400px;
    margin: 10px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

footer {
    background-color: #ffcc00;
    padding: 10px;
    color: #333;
    position: fixed;
    width: 100%;
    bottom: 0;
    left: 0;
}

body.dark-mode footer {
    background-color: #ffb300;
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
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<h2>Enter your username below to get 0.01-10 free Duinocoin Once a Day!</h2>


<form action="" method="post" onsubmit="return validateForm()">
    <label for="username">Enter your username:</label>
    <input type="text" id="username" name="username" required>
    <div class="g-recaptcha" data-sitekey="ENTERYOURRECAPTCHASITEKEYHERE "></div>
    <input type="submit" value="Get DUCOS">
</form>
<script>
function validateForm() {
    var response = grecaptcha.getResponse();
    if (!response) {
        alert('Please complete the ReCaptcha.');
        return false;
    }
    return true;
}
</script>

<a href="https://discord.gg/HUbHqUQUD2">Join our Discord server!<br></a>





</body>
</html>
<?php
// File path to store the cooldown data
$cooldownFile = 'cooldown.txt';

// Initialize the cooldown data array
$cooldownData = array();
$cooldownTime = 86400;
// Read the cooldown data from the file if it exists
if (file_exists($cooldownFile)) {
    $cooldownData = json_decode(file_get_contents($cooldownFile), true);
}  else {
        // Create the cooldown file if it doesn't exist
        file_put_contents($cooldownFile, json_encode($cooldownData));
    }


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = "USername_here";
    $recipient = $_POST["username"];
    $password = "PasswordHere";
    $memo = "Faucet claim";
    // number randomiser. if you want a single number deleted the mt_rand and replace with a integer and a ;
    $amount = mt_rand(1, 1000) / 100;
    $currentTime = time(); // Get the current time
    

     // Check if the cooldown data array is not null
     if ($cooldownData !== null) {
        // Check if the recipient's username is in the cooldown list
        if (array_key_exists($recipient, $cooldownData) && $currentTime - $cooldownData[$recipient] < $cooldownTime) {
            $remainingTime = $cooldownTime - ($currentTime - $cooldownData[$recipient]);
            echo "Please wait for " . $remainingTime . " Seconds before claiming again.";
            exit;
        }
    }
    
   
    // Your existing code for sending transaction request...

    $url = "https://server.duinocoin.com/transaction/?username=$username&password=$password&recipient=$recipient&amount=$amount&memo=$memo";

    $response = file_get_contents($url);

    if ($response === false) {
        echo "Error: Failed to send transaction request.";
    } else {
        $transactionData = json_decode($response, true);

        if (isset($transactionData['success']) && $transactionData['success']) {
            echo "Transaction successful. Sent ". $amount . " to " . $recipient, $transactionData['transaction'];
            // Update the last submit time in the cooldown data
            $cooldownData[$recipient] = $currentTime;
            // Save the updated cooldown data to the file
            file_put_contents($cooldownFile, json_encode($cooldownData));
            
        } else {
            echo "Error: Transaction failed. Reason: " . (isset($transactionData['error']) ? $transactionData['error'] : 'Unknown');
        }
    }
}


?>


<br>
<br>
<br>
<br>
<br>
<br>
<p>.</p>
<footer>
    <p>If you want to donate, send ducos to katfaucet!</p>
</footer>
</body>
</html>
