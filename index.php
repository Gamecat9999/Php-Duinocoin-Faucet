<html>
<head>

</head>
<script>
// Set the cooldown time in seconds
var cooldownTime = 86400;

// Function to check if the form can be submitted
function validateForm() {
    var currentTime = new Date().getTime() / 1000;
    var lastSubmitTime = localStorage.getItem('lastSubmitTime');

    if (lastSubmitTime && currentTime - lastSubmitTime < cooldownTime) {
        var remainingTime = Math.ceil(cooldownTime - (currentTime - lastSubmitTime));
        alert("Please wait for " + remainingTime + " seconds before claiming again.");
        return false;
    }

    return true;
}
</script>
<body>
<title>KatFaucet🐱</title>
<style>
body.dark-mode {
    background-color: black;
    color: white;
}

body.dark-mode h1,
body.dark-mode h2 {
    color: white;
}

body.dark-mode img {
    filter: brightness(100%);
}

body.dark-mode input[type="text"],
body.dark-mode input[type="submit"] {
    background-color: #333;
    color: white;
}

body.dark-mode form {
    background-color: #555;
}

body.dark-mode a {
    color: lightblue;
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

<h1>Katfaucet😻</h1>
<h2>Enter your username below to get 2 free Duinocoin Once a day!</h2>

<img src="https://catfaucet.alwaysdata.net/photos/cat4.jpg" alt="Cute cat!">

<img src="https://catfaucet.alwaysdata.net/photos/cat.jpg" alt="Cute cat!">

<form action="" method="post" onsubmit="return validateForm()">
    <label for="username">Enter your username:</label>
    <input type="text" id="username" name="username" required>
    <input type="submit" value="Get DUCOS">
</form>

<?php
// File path to store the cooldown data
$cooldownFile = 'cooldown.txt';
// Initialize the cooldown data array
$cooldownData = array();
$cooldownTime = 86400;
// Read the cooldown data from the file if it exists
if (file_exists($cooldownFile)) {
    $cooldownData = json_decode(file_get_contents($cooldownFile), true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = "katfaucet";
    $recipient = $_POST["username"];
    $password = "Stayout1";
    $memo = "KatFaucet CLAIM";
    $amount = "10";
    $currentTime = time(); // Get the current time
     // Check if the cooldown data array is not null
     if ($cooldownData !== null) {
        // Check if the recipient's username is in the cooldown list
        if (array_key_exists($recipient, $cooldownData) && $currentTime - $cooldownData[$recipient] < $cooldownTime) {
            echo "Please wait for the cooldown period to expire.";
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
            echo "Transaction successful." . $transactionData['transaction'];
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
<h2> If you want to donate send ducos to katfaucet!</h2>
</body>
</html>