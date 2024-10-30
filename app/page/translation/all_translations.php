<?php
session_start();
require __DIR__ . '/../../../config/bd.php'; // Подключение к БД

// Инициализация переменной для поиска
$search = $_GET['search'] ?? '';

// SQL-запрос для поиска по ключу и переводам
$sql = "
    SELECT key_name, 
           GROUP_CONCAT(CASE WHEN lang_code = 'ru' THEN translation_text END) AS ru,
           GROUP_CONCAT(CASE WHEN lang_code = 'uz' THEN translation_text END) AS uz,
           GROUP_CONCAT(CASE WHEN lang_code = 'en' THEN translation_text END) AS en
    FROM translations
    WHERE (:search = '' OR key_name LIKE :search_key OR translation_text LIKE :search_text)
    GROUP BY key_name
    ORDER BY key_name ASC
";

// Выполнение запроса с параметрами
$stmt = $pdo->prepare($sql);
$stmt->execute([
    'search' => $search,
    'search_key' => "%$search%",
    'search_text' => "%$search%"
]);

$translations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Обработка сохранения изменений
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $key_name = $_POST['key_name'];
    $ru = $_POST['ru'];
    $uz = $_POST['uz'];
    $en = $_POST['en'];

    // Обновление переводов
    $pdo->prepare("UPDATE translations SET translation_text = ? WHERE key_name = ? AND lang_code = 'ru'")
        ->execute([$ru, $key_name]);
    $pdo->prepare("UPDATE translations SET translation_text = ? WHERE key_name = ? AND lang_code = 'uz'")
        ->execute([$uz, $key_name]);
    $pdo->prepare("UPDATE translations SET translation_text = ? WHERE key_name = ? AND lang_code = 'en'")
        ->execute([$en, $key_name]);

    header("Location: all_translations.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все переводы</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/sidebarr.css">
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
                    <li><a href="../text/add_text.php" class="dropdown-item">Добавить текст</a></li>
                    <li><a href="../text/all_text.php" class="dropdown-item">Все тексты</a></li>
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
                    <li><a href="./translations.php" class="dropdown-item">Добавить перевод</a></li>
                    <li><a href="./all_translations.php" class="dropdown-item">Все переводы</a></li>
                </ul>
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
        <div class="d-flex justify-content-between">
            <h2>Все переводы</h2>
            <a href="./translations.php" class="btn btn-primary">Добавить новый перевод</a>
        </div>

        <!-- Форма поиска с кнопкой сброса -->
        <form method="GET" class="d-flex my-3 ">
            <input type="text" name="search" class="form-control me-2 " placeholder="Поиск по ключу или переводу"
                value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-outline-primary me-2">Искать</button>
            <a href="all_translations.php" class="btn btn-secondary">Сбросить</a>
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Ключ текста</th>
                    <th>Русский</th>
                    <th>Узбекский</th>
                    <th>Английский</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($translations)): ?>
                    <tr>
                        <td colspan="6" class="text-center">Нет данных</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($translations as $index => $translation): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($translation['key_name']) ?></td>
                            <td><?= htmlspecialchars($translation['ru'] ?? '') ?></td>
                            <td><?= htmlspecialchars($translation['uz'] ?? '') ?></td>
                            <td><?= htmlspecialchars($translation['en'] ?? '') ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- Кнопка редактирования -->
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-key_name="<?= htmlspecialchars($translation['key_name']) ?>"
                                        data-ru="<?= htmlspecialchars($translation['ru'] ?? '') ?>"
                                        data-uz="<?= htmlspecialchars($translation['uz'] ?? '') ?>"
                                        data-en="<?= htmlspecialchars($translation['en'] ?? '') ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                            class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706l-1 1a.5.5 0 0 1-.708 0l-1-1a.5.5 0 0 1 .708-.708l.646.647-.646-.647a.5.5 0 0 1-.708 0z" />
                                            <path d="M13.5 3.5l-10 10V14h.5l10-10-.5-.5zm0-1L3 13V15h2l10-10V4l-1-.5z" />
                                        </svg>
                                    </button>

                                    <!-- Кнопка удаления -->
                                    <form method="POST" action="delete_translation.php" class="d-inline">
                                        <input type="hidden" name="key_name" value="<?= htmlspecialchars($translation['key_name']) ?>">
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Вы уверены, что хотите удалить этот перевод?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0,0,256,256">
                                                <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal">
                                                    <g transform="scale(4,4)">
                                                        <path d="M28,6c-2.209,0 -4,1.791 -4,4v2h-0.40039l-13.59961,2v3h44v-3l-13.59961,-2h-0.40039v-2c0,-2.209 -1.791,-4 -4,-4zM28,10h8v2h-8zM12,19l2.70117,33.32227c0.168,2.077 1.90428,3.67773 3.98828,3.67773h26.62305c2.084,0 3.81733,-1.59878 3.98633,-3.67578l2.625,-32.32422zM20,26c1.105,0 2,0.895 2,2v23h-3l-1,-23c0,-1.105 0.895,-2 2,-2zM32,26c1.657,0 3,1.343 3,3v22h-6v-22c0,-1.657 1.343,-3 3,-3zM44,26c1.105,0 2,0.895 2,2l-1,23h-3v-23c0,-1.105 0.895,-2 2,-2z"></path>
                                                    </g>
                                                </g>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Модальное окно -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Редактировать перевод</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="key_name" id="key_name">
                    <div class="mb-3">
                        <label for="ru" class="form-label">Русский</label>
                        <input type="text" class="form-control" name="ru" id="ru">
                    </div>
                    <div class="mb-3">
                        <label for="uz" class="form-label">Узбекский</label>
                        <input type="text" class="form-control" name="uz" id="uz">
                    </div>
                    <div class="mb-3">
                        <label for="en" class="form-label">Английский</label>
                        <input type="text" class="form-control" name="en" id="en">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>