<html>

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
<h2>Enter your username below to get 0.01-10 free Duinocoin Once a Day!</h2>

<img src="https://catfaucet.alwaysdata.net/photos/cat4.jpg" alt="Cute cat!">
<img src="https://catfaucet.alwaysdata.net/photos/cat.jpg" alt="Cute cat!">

<form action="" method="post" onsubmit="return validateForm()">
    <label for="username">Enter your username:</label>
    <input type="text" id="username" name="username" required>
    <label for="captcha">Solve this math problem: <?php $num1 = rand(1, 10); $num2 = rand(1, 10); echo $num1 . " + " . $num2; ?> =</label>
    <input type="hidden" name="captcha_answer" value="<?php echo $num1 + $num2; ?>">
    <input type="text" id="captcha" name="captcha" required>
    <input type="submit" value="Get DUCOS">
</form>

<?php
// File path to store the cooldown data
$cooldownFile = 'cooldown.txt';
$lastClaimedUsernamesFile = 'last_claimed_usernames.txt';
// Initialize the cooldown data array
$cooldownData = array();
//set the cooldown here
$cooldownTime = 86400;
// Read the cooldown data from the file if it exists
if (file_exists($cooldownFile)) {
    $cooldownData = json_decode(file_get_contents($cooldownFile), true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = "Your username here";
    $recipient = $_POST["username"];
    $password = "Password here";
    $memo = "KatFaucet Claim";
    // this makes the amount random
    $amount = mt_rand(1, 1000) / 100;
    $currentTime = time(); // Get the current time
    // Validate the captcha answer
    $captchaAnswer = $_POST["captcha_answer"];
    $userCaptcha = $_POST["captcha"];

    if ($captchaAnswer != $userCaptcha) {
        echo "Error: Invalid captcha answer.";
        exit;
    }
    

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
<a href="https://discord.gg/HUbHqUQUD2">Join our Discord server!</a>
<h2> If you want to donate send ducos to katfaucet!</h2>
<h2> Donate to get your Pets photo displayed here! make sure to join our discord server to send a photo!</h2>
<img src="https://catfaucet.alwaysdata.net/photos/donator.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator1.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator2.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator3.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator4.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator5.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator6.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator7.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator8.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator9.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator10.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator11.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator12.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator13.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator14.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator15.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator16.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator17.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator18.jpg">
<img src="https://catfaucet.alwaysdata.net/photos/donator19.jpg">
</body>
</html>
