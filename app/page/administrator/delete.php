<?php
session_start();
require __DIR__ . '/../../../config/bd.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_POST['id']; // Получаем ID администратора для удаления

    try {
        // Выполняем удаление администратора
        $stmt = $pdo->prepare("DELETE FROM administrators WHERE id = ?");
        $stmt->execute([$adminId]);

        header("Location: profil.php"); // Перенаправляем обратно на страницу профиля
        exit;
    } catch (PDOException $e) {
        echo "Ошибка при удалении администратора: " . $e->getMessage();
        exit;
    }
} else {
    echo "Некорректный запрос.";
}
?>
