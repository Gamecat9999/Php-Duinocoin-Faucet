<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <h1>Account Balance</h1>
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
