<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


session_start();

function redirect($url)
{
    header('Location: ' . $url);
    exit();
}

// Получаем путь запроса
$request = trim($_SERVER['REQUEST_URI'], '/');

// Перенаправляем на страницу логина, если путь пустой или указан 'login'
if (empty($request) || $request === 'login') {
    redirect('app/auth/login.php');
} elseif ($request === 'bd') {
    redirect('bd.php');
} 
    echo 'Страница не найдена';

