<?php

if (isset($_SESSION['administrators'])) {
    header('Location: login.php'); // Перенаправление на страницу входа
    exit();
}
// Предотвращение кэширования страницы
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Прокси.


require '../../config/bd.php'; // Подключение к базе данных
$controller = new LoginController($pdo); // Создаем экземпляр контроллера
class LoginController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Метод для аутентификации пользователя
    public function login($username, $password)
    {
        // Поиск пользователя в базе данных по имени пользователя
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM administrators WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Проверка, существует ли пользователь и правильность пароля
            if ($user && password_verify($password, $user['password'])) {
                // Установка сессии с данными пользователя
                $_SESSION['user'] = $user['username'];
                $_SESSION['role'] = $user['role'];
              

                return true; // Возвращаем true, если авторизация успешна
            }

            return false; // Возвращаем false, если учетные данные неверны
        } catch (PDOException $e) {
            // Обработка ошибок базы данных
            die("Ошибка базы данных: " . $e->getMessage());
        }
    }

    // Метод для выхода из системы (очистка сессии)
    public function logout()
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: login.php'); // Перенаправление на страницу логина после выхода
        exit;
    }
}
