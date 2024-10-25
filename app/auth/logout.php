<?php
session_start();

// Очистка всех переменных сессии.
$_SESSION = array();

// Если нужно уничтожить сессию, удалите также и куки сессии.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// В конце концов, уничтожаем сессию.
session_destroy();

// Перенаправляем на страницу входа:
header("Location: ./login.php");
exit;
?>
