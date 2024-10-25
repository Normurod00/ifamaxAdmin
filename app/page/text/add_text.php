<?php
session_start();
require __DIR__ . '/../../../config/bd.php'; // Подключение к базе данных через PDO

// Переменные для сообщения
$message = $_SESSION['message'] ?? '';
$alertType = $_SESSION['alertType'] ?? '';

unset($_SESSION['message'], $_SESSION['alertType']); // Очищаем сообщения после отображения

// Если форма отправлена
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Извлечение данных из POST-запроса
    $title = $_POST['title'] ?? '';
    $heading = $_POST['heading'] ?? '';
    $content = $_POST['content'] ?? '';
    $description = $_POST['description'] ?? '';
    $url = $_POST['url'] ?? '';

    try {
        // Вставка данных в таблицу
        $sql = "INSERT INTO site_text (title, heading, content, description, url) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $heading, $content, $description, $url]);

        // Устанавливаем сообщение об успешном добавлении
        $_SESSION['message'] = "Текст успешно добавлен!";
        $_SESSION['alertType'] = 'success';

        // Перенаправляем на ту же страницу для предотвращения повторного добавления
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        // Устанавливаем сообщение об ошибке
        $_SESSION['message'] = "Ошибка при добавлении данных: " . $e->getMessage();
        $_SESSION['alertType'] = 'danger';

        // Перенаправляем на ту же страницу для отображения ошибки
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление текста</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/sidebarr.css">
</head>

<body class="d-flex">

<div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 280px; height:100vh">
        <a href="../admin_panel.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <svg class="bi pe-none me-2" width="40" height="32">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4">Админка</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="../admin_panel.php" class="home">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
                        <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z" />
                    </svg>
                    Главная
                </a>
            </li>
            <hr>
            <li class="dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0" />
                    <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1z" />
                </svg>
                <a href="#" class="dropdown-toggle btn btn-toggle align-items-center text-white rounded" role="button" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Изображения
                </a>
                <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                    <li><a href="../image/add_image.php" class="dropdown-item">Добавить Изображение</a></li>
                    <li><a href="../image/all_image.php" class="dropdown-item">Все Изображения</a></li>
                </ul>
            </li>
            <hr>
            <li class="dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-card-text" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z" />
                    <path d="M3 5.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5M3 8a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 8m0 2.5a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5" />
                </svg>
                <a href="#" class="dropdown-toggle btn btn-toggle align-items-center text-white rounded" role="button" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Текст
                </a>
                <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                    <li><a href="./add_text.php" class="dropdown-item">Добавить текст</a></li>
                    <li><a href="./all_text.php" class="dropdown-item">Все тексты</a></li>
                </ul>
            </li>
            <hr>
            <li>
                <a href="#" class="nav-link text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
                        <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1 1 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4 4 0 0 1-.128-1.287z" />
                        <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243z" />
                    </svg>
                    Ссылки
                </a>
            </li>
        </ul>
        <hr>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                </svg>
                <strong class="p-3">Администратор</strong>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark text-small shadow">
                <li><a class="dropdown-item" href="../administrator/profil.php">Профиль</a></li>
                <li><a class="dropdown-item" href="#">Настройки</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="../../auth/logout.php">Выйти</a></li>
            </ul>
        </div>
    </div>

    <div class="container-fluid mt-5">
        <h2>Добавить текст</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= htmlspecialchars($alertType) ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Заголовок</label>
                <input type="text" id="title" name="title" class="form-control">
            </div>
            <div class="mb-3">
                <label for="heading" class="form-label">Подзаголовок</label>
                <input type="text" id="heading" name="heading" class="form-control">
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Описание</label>
                <textarea id="description" name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="mb-3">
                <label for="url" class="form-label">Ссылка</label>
                <input type="text" id="url" name="url" class="form-control" placeholder="Введите URL">
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Текст</label>
                <textarea id="content" name="content" class="form-control" rows="5"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Добавить</button>
        </form>

        <a href="./all_text.php" class="btn btn-secondary mt-3">Просмотреть все тексты</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
