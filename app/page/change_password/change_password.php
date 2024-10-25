<?php
session_start();
require __DIR__ . '/../../../config/bd.php'; // Подключение к базе данных через PDO

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newPassword = $_POST['new_password'];

    // Хешируем новый пароль
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    try {
        // Обновляем пароль в базе данных
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $_SESSION['admin_id']]);

        echo "Пароль успешно изменён!";
    } catch (PDOException $e) {
        echo "Ошибка при изменении пароля: " . $e->getMessage();
    }
}
?>
