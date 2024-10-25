 <?php
  session_start();

  // Проверяем, авторизован ли пользователь
  if (isset($_SESSION['administrators'])) {
    header('Location: ../auth/login.php'); // Перенаправляем на страницу входа
    exit;
  }

  // Обработка нажатия кнопки выхода
  if (isset($_POST['logoutbtn'])) {
    // Очищаем сессию
    $_SESSION = array();
    session_destroy();
    // Удаляем куки сессии, если они используются
    if (ini_get("session.use_cookies")) {
      $params = session_get_cookie_params();
      setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
      );
    }
    header("Location: login.php"); // Перенаправляем на страницу входа
    exit;
  }

  $role = $_SESSION['role'] ?? 'guest'; // Получаем роль пользователя

  ?>

 <!DOCTYPE html>
 <html lang="ru">

 <head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Административная панель</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
   <link rel="stylesheet" type="text/css" href="../../public/css/admin_panel.css">
   <link rel="stylesheet" href="../../../public/css/sidebarr.css">
 </head>

 <body>

   <body>
   <div class="sidebar d-flex flex-column flex-shrink-0 p-3 bg-dark text-white" style="width: 280px; height:100vh">
        <a href="./admin_panel.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
            <svg class="bi pe-none me-2" width="40" height="32">
                <use xlink:href="#bootstrap"></use>
            </svg>
            <span class="fs-4">Админка</span>
        </a>
        <hr>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="./admin_panel.php" class="home">
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
                    <li><a href="../page/image/add_image.php" class="dropdown-item">Добавить Изображение</a></li>
                    <li><a href="../page/image/all_image.php" class="dropdown-item">Все Изображения</a></li>
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
                    <li><a href="../page/text/add_text.php" class="dropdown-item">Добавить текст</a></li>
                    <li><a href="../page/text/all_text.php" class="dropdown-item">Все тексты</a></li>
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
                <li><a class="dropdown-item" href="../page/administrator/profil.php">Профиль</a></li>
                <li><a class="dropdown-item" href="#">Настройки</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="../../auth/logout.php">Выйти</a></li>
            </ul>
        </div>
    </div>
     <div class="container-fluid">
       <h1 class="h3">Добро пожаловать в административную панель!</h1>
       <p>Здесь вы можете управлять данными вашего сайта, включая текст, изображения и файлы.</p>
     </div>


     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
   </body>

 </html>