<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

use Classes\Auth;
use Exception;

try {
    $auth = new Auth;

    if (array_key_exists('PHP_AUTH_USER', $_SERVER) && array_key_exists('PHP_AUTH_PW', $_SERVER)) {
        $auth->checkUser([
            'login' => $_SERVER['PHP_AUTH_USER'],
            'password' => $_SERVER['PHP_AUTH_PW'],
        ]);
    }

    if (!$auth->isOk()) {
        throw new Exception('Доступ запрещён', 404);
    }
} catch (Exception $e) {
    die(json_encode(['error' => $e->getMessage()]));
}

// получаем uri вида /api/users?test=test
// разбиваем до знака вопроса
$requestPath = explode('?', $_SERVER['REQUEST_URI']);

// разбиваем по слешам
$requestUri = explode('/', trim($requestPath[0], '/'));

// проверяем на наличие api в ссылке
if (array_shift($requestUri) === 'api' && !count($requestUri)) {
    throw new Exception('API не найден', 404);
}

// получаем имя метода и устанавливаем первую букву в верхний регистр
$apiName = ucfirst($requestUri[0]);

// генерируем путь к классу
$generateApiPath = implode('\\', ['Classes', $apiName]);

// проверяем на наличие класса
if (!class_exists($generateApiPath)) {
    throw new Exception("API: Модуль $apiName не найден", 404);
}

// вызываем класс
echo (new $generateApiPath)->run();
