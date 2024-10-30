<?php
session_start();
require __DIR__ . '/../../../config/bd.php'; // Подключение к базе данных через PDO

// Обработчик удаления записи
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Удаление записи
    $stmt = $pdo->prepare("DELETE FROM site_text WHERE id = ?");
    $stmt->execute([$delete_id]);

    // Сброс автоинкремента
    $pdo->exec("SET @num := 0;");
    $pdo->exec("UPDATE site_text SET id = (@num := @num + 1);");
    $pdo->exec("ALTER TABLE site_text AUTO_INCREMENT = 1;");

    header("Location: all_text.php");
    exit;
}

// Инициализация переменных для фильтров и сортировки
$title = $_GET['title'] ?? '';
$heading = $_GET['heading'] ?? '';
$description = $_GET['description'] ?? '';
$url = $_GET['url'] ?? '';
$content = $_GET['content'] ?? '';
$order = $_GET['order'] ?? 'asc'; // Порядок сортировки: 'asc' или 'desc'

// Подготовка SQL-запроса с фильтрацией и сортировкой
$sql = "SELECT * FROM site_text WHERE 1=1";
$params = [];

if ($title !== '') {
    $sql .= " AND title LIKE ?";
    $params[] = "%$title%";
}
if ($heading !== '') {
    $sql .= " AND heading LIKE ?";
    $params[] = "%$heading%";
}
if ($description !== '') {
    $sql .= " AND description LIKE ?";
    $params[] = "%$description%";
}
if ($url !== '') {
    $sql .= " AND url LIKE ?";
    $params[] = "%$url%";
}
if ($content !== '') {
    $sql .= " AND content LIKE ?";
    $params[] = "%$content%";
}

// Добавляем сортировку по времени создания
$sql .= " ORDER BY created_at $order";

// Выполнение запроса к базе данных
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все тексты</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/sidebarr.css">
    <link rel="stylesheet" href="../../../public/css/text.css">
</head>

<body class="d-flex">

    <div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 280px;">
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
            <li class="dropdown">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-translate" viewBox="0 0 16 16">
                        <path d="M4.545 6.714 4.11 8H3l1.862-5h1.284L8 8H6.833l-.435-1.286zm1.634-.736L5.5 3.956h-.049l-.679 2.022z" />
                        <path d="M0 2a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v3h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-3H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zm7.138 9.995q.289.451.63.846c-.748.575-1.673 1.001-2.768 1.292.178.217.451.635.555.867 1.125-.359 2.08-.844 2.886-1.494.777.665 1.739 1.165 2.93 1.472.133-.254.414-.673.629-.89-1.125-.253-2.057-.694-2.82-1.284.681-.747 1.222-1.651 1.621-2.757H14V8h-3v1.047h.765c-.318.844-.74 1.546-1.272 2.13a6 6 0 0 1-.415-.492 2 2 0 0 1-.94.31" />
                    </svg>
                    <a href="./all_translations.php" class="dropdown-toggle btn btn-toggle align-items-center text-white rounded" role="button" id="adminDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Переводчик
                </a>
                <ul class="dropdown-menu" aria-labelledby="adminDropdown">
                    <li><a href="../translation/translations.php" class="dropdown-item">Добавить перевод</a></li>
                    <li><a href="../translation/all_translations.php" class="dropdown-item">Все переводы</a></li>
                </ul>
            </li>
            
        </ul>
        <hr>
        <div class="dropdown">
            <a href="/" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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
        <div class="d-flex justify-content-between">
            <h2>Все тексты</h2>
            <div>
                <a href="?order=asc" class="btn btn-outline-primary">Сортировать по возрастанию</a>
                <a href="?order=desc" class="btn btn-outline-secondary">Сортировать по убыванию</a>
            </div>
            <a href="./add_text.php" class=" add_text btn btn-secondary d-flex align-items-center ">Добавить новое текст</a>

        </div>

        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-2">
                <input type="text" name="title" class="form-control" placeholder="Заголовок"
                    value="<?= htmlspecialchars($title) ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="heading" class="form-control" placeholder="Подзаголовок"
                    value="<?= htmlspecialchars($heading) ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="description" class="form-control" placeholder="Описание"
                    value="<?= htmlspecialchars($description) ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="url" class="form-control" placeholder="Ссылка"
                    value="<?= htmlspecialchars($url) ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="content" class="form-control" placeholder="Текст"
                    value="<?= htmlspecialchars($content) ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Поиск</button>
            </div>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Заголовок</th>
                    <th>Подзаголовок</th>
                    <th>Описание</th>
                    <th>Ссылка</th>
                    <th>Текст</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Нет данных</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($rows as $index => $row): ?>
                        <tr>
                            <td><?= $index + 1 ?></td> <!-- Порядковый номер -->
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><?= htmlspecialchars($row['heading']) ?></td>
                            <td><?= htmlspecialchars($row['description']) ?></td>
                            <td><?= htmlspecialchars($row['url']) ?></td>
                            <td><?= htmlspecialchars($row['content']) ?></td>
                            <td><?= htmlspecialchars($row['created_at']) ?></td>
                            <td>
                                <a href="all_text.php?delete_id=<?= $row['id'] ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Вы уверены, что хотите удалить?');">
                                    Удалить
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>