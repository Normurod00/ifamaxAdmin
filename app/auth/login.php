<?php
session_start();
echo "login";

header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
header("Pragma: no-cache"); // HTTP 1.0.
header("Expires: 0"); // Прокси.

require '../../config/bd.php'; // Подключение к базе данных
require '../Controller/loginController.php'; // Подключение контроллера
$controller = new LoginController($pdo); // Создаем экземпляр контроллера

$error = ''; // Переменная для сообщений об ошибках

// Проверка сессии, чтобы узнать, залогинен ли пользователь
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: ../page/admin_panel.php"); // Если пользователь залогинен, перенаправляем на админ панель
    exit;
}

// Логика обработки формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        // Используем метод login() из контроллера для проверки учетных данных
        if ($controller->login($username, $password)) {
            $_SESSION['logged_in'] = true;
            header("Location: ../page/admin_panel.php"); // Перенаправляем на админ панель
            exit;
        } else {
            $error = "Неправильное имя пользователя или пароль!";
        }
    } catch (Exception $e) {
        $error = "Ошибка: " . $e->getMessage(); // Отображение сообщения об ошибке
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../public/css/login.css">
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }
    </script>
</head>

<body>
    <div class="container-tight">
        <div class="text-center mb-4">
            <a href="/" class="navbar-brand navbar-brand-autodark">
                <img src="../../public/img/logo.png" class="navbar-brand-image" style="width: 250px; height: 70px;" alt="logo BRB">
            </a>
        </div>
        <form class="card card-md" method="POST" action="">
            <div class="card-body">
                <h2 class="card-title h2 text-center mb-4">Авторизация</h2>

                <div id="error-message" class="alert alert-danger <?php echo empty($error) ? 'd-none' : '' ?>" role="alert">
                    <?php echo $error; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Имя пользователя</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Введите имя пользователя" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <div class="input-group input-group-flat">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Введите пароль" required>
                        <span class="input-group-text">
                            <a href="#" class="link-secondary" title="Показать пароль" onclick="togglePasswordVisibility(); return false;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                                </svg>
                            </a>
                        </span>
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn btn-danger w-100">Авторизоваться</button>
                </div>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
