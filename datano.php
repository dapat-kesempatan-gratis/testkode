<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomor = isset($_POST['nomor']) ? $_POST['nomor'] : '';

    if (empty($nomor) || !is_numeric($nomor)) {
        // Jika nomor HP tidak valid, kembali ke halaman sebelumnya
        header('Location: index.php');
        exit;
    }

    // Panggil fungsi handle_no_hp dengan nomor yang diterima
    handle_no_hp($nomor);
} else {
    // Jika metode pengiriman bukan POST, kembali ke halaman utama
    header('Location: index.php');
    exit;
}
?>
