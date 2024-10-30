<?php
require __DIR__ . '/../../../config/bd.php'; // Подключение к БД

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key_name = $_POST['key_name'];

    // Удаление перевода по ключу
    $stmt = $pdo->prepare("DELETE FROM translations WHERE key_name = ?");
    $stmt->execute([$key_name]);

    header("Location: all_translations.php");
    exit;
}
?>
