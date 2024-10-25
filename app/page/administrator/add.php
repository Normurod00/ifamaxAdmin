<?php
session_start();
require __DIR__ . '/../../../config/bd.php'; // Подключение к БД

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO administrators (username, phone, password) VALUES (?, ?, ?)");
        $stmt->execute([$username, $phone, $password]);

        header("Location: profil.php");
        exit;
    } catch (PDOException $e) {
        echo "Ошибка при добавлении администратора: " . $e->getMessage();
    }
}
?>
