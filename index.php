<?php

// устанавливаем, что все домены могут обращаться в ресурсам сайта
header("Access-Control-Allow-Origin: *");
// устанавливаем, что все запросы могут быть использованы для доступа к ресурсам
header("Access-Control-Allow-Methods: *");
// устанавливаем тип передаваемых данных
header("Content-Type: application/json");

// Разбираем url
$url = (isset($_GET['req'])) ? $_GET['req'] : '';
$urls = explode('/', rtrim($url, '/'));

// Определяем роутер и url data
$router = $urls[0];

// Подключаем файл-роутер и запускаем главную функцию
if (file_exists(__DIR__ . '/Classes/' . $router . '.php')) {
    require_once 'Classes/' . $router . '.php';
} else {
    throw new RuntimeException('API Not Found', 404);
}

//try {
//
//} catch (Exception $e) {
//
//
////    echo json_encode(Array('error' => $e->getMessage()));
//}