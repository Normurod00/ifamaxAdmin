<?php
session_start();
require __DIR__ . '/../../../config/bd.php'; // Подключение к базе данных через PDO

// Инициализируем фильтры
$titleFilter = $_GET['title'] ?? '';
$descriptionFilter = $_GET['description'] ?? '';
$linkFilter = $_GET['link'] ?? '';

try {
    // Базовый SQL-запрос
    $sql = "SELECT * FROM images WHERE 1=1";

    // Массив для параметров запроса
    $params = [];

    // Добавляем условия для фильтрации, если поля не пусты
    if (!empty($titleFilter)) {
        $sql .= " AND title LIKE ?";
        $params[] = '%' . $titleFilter . '%';
    }
    if (!empty($descriptionFilter)) {
        $sql .= " AND description LIKE ?";
        $params[] = '%' . $descriptionFilter . '%';
    }
    if (!empty($linkFilter)) {
        $sql .= " AND image_url LIKE ?";
        $params[] = '%' . $linkFilter . '%';
    }

    // Сортируем результаты по дате создания
    $sql .= " ORDER BY created_at DESC";

    // Подготавливаем и выполняем запрос
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $images = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Ошибка при получении изображений: " . $e->getMessage();
    exit;
}

// Удаление изображения
if (isset($_GET['delete'])) {
    $imageId = $_GET['delete'];
    try {
        // Получаем путь к изображению
        $stmt = $pdo->prepare("SELECT image_url FROM images WHERE id = ?");
        $stmt->execute([$imageId]);
        $image = $stmt->fetch();

        if ($image) {
            // Удаляем файл, если он существует
            $imagePath = __DIR__ . '/../../../' . ltrim($image['image_url'], '/');
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Удаляем запись из базы данных
            $stmt = $pdo->prepare("DELETE FROM images WHERE id = ?");
            $stmt->execute([$imageId]);

            // Сбрасываем автоинкремент
            resetAutoIncrement($pdo);

            // Перенаправляем на страницу после удаления
            header("Location: all_image.php");
            exit;
        }
    } catch (PDOException $e) {
        echo "Ошибка при удалении изображения: " . $e->getMessage();
        exit;
    }
}

// Функция для сброса автоинкремента
function resetAutoIncrement($pdo)
{
    try {
        $pdo->exec("SET @num := 0;"); // Переменная для пересчета
        $pdo->exec("UPDATE images SET id = (@num := @num + 1);"); // Пересчитываем ID
        $pdo->exec("ALTER TABLE images AUTO_INCREMENT = 1;"); // Сбрасываем автоинкремент
    } catch (PDOException $e) {
        echo "Ошибка при сбросе автоинкремента: " . $e->getMessage();
    }
}
?>



<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все изображения</title>
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
                    <li><a href="./add_image.php" class="dropdown-item">Добавить Изображение</a></li>
                    <li><a href="./all_image.php" class="dropdown-item">Все Изображения</a></li>
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
                    <li><a href="../text/add_text.php" class="dropdown-item">Добавить текст</a></li>
                    <li><a href="../text/all_text.php" class="dropdown-item">Все тексты</a></li>
                </ul>
            </li>
            <hr>
            <li>
                <a href="#" class="home">
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
        <h2>Все изображения</h2>
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-center mb-4">
            <div class="col">
                <input type="text" name="title" class="form-control" placeholder="Название"
                    value="<?= htmlspecialchars($titleFilter) ?>">
            </div>
            <div class="col">
                <input type="text" name="description" class="form-control" placeholder="Описание"
                    value="<?= htmlspecialchars($descriptionFilter) ?>">
            </div>
            <div class="col">
                <input type="text" name="link" class="form-control" placeholder="Ссылка"
                    value="<?= htmlspecialchars($linkFilter) ?>">
            </div>
            <div class="col">
                <button type="submit" class="btn btn-primary">Поиск</button>
            </div>
        </form>


        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Изображение</th>
                    <th>Ссылка</th>
                    <th>Путь</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($images as $image): ?>
                    <tr>
                        <td><?= htmlspecialchars($image['id']) ?></td>
                        <td><?= htmlspecialchars($image['title']) ?></td>
                        <td><?= htmlspecialchars($image['description']) ?></td>
                        <td>
                            <img src="<?= htmlspecialchars($image['file_path']) ?>"
                                alt="Image" class="img-thumbnail"
                                style="width: 100px; height: auto;">
                        </td>
                        <td>
                            <a href="<?= htmlspecialchars($image['file_path']) ?>" target="_blank">
                                <?= htmlspecialchars($image['image_url']) ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($image['file_path']) ?>
                        </td>
                        <td><?= htmlspecialchars($image['created_at']) ?></td>
                        <td>
                            <a href="all_image.php?delete=<?= $image['id'] ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Вы уверены, что хотите удалить это изображение?')">
                                Удалить
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>



        </table>

        <a href="add_image.php" class="btn btn-secondary mt-3">Добавить новое изображение</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>