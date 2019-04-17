<?php

declare (strict_types=1);

namespace Classes;

use Classes\Helper;
use Exception;

abstract class Api
{
    use Helper;

    protected $method = ''; // Метод запроса

    protected $requestPath = []; // Путь запроса

    protected $requestParams = []; // Параметры запроса

    protected $action = ''; //Название метода для выполнения

    public function __construct()
    {
        // устанавливаем, что все домены могут обращаться в ресурсам сайта
        header("Access-Control-Allow-Origin: *");
        // устанавливаем, что все запросы могут быть использованы для доступа к ресурсам
        header("Access-Control-Allow-Methods: *");
        // устанавливаем тип передаваемых данных
        header("Content-Type: application/json");

        // Определение метода запроса
        $this->method = $_SERVER['REQUEST_METHOD'];

        // Разбиваем uri до параметров и определяем массив из uri
        $apiPath = explode('?', $_SERVER['REQUEST_URI']);
        $this->requestPath = explode('/', trim($apiPath[0], '/'));

        // Определение параметров
        $this->requestParams = $_REQUEST;

        //Определение действия для обработки
        $this->action = $this->getAction();
    }

    public function run()
    {
        return $this->{$this->action}();
    }

    protected function getAction()
    {
        $method = $this->method;
        $action = null;

        switch ($method) {
            case 'GET':
                if (count($this->requestPath) > 2) {
                    return 'viewAction';
                } else {
                    return 'indexAction';
                }
                break;
            case 'POST':
                if (count($this->requestPath) > 2) {
                    switch ($this->requestPath[2]) {
                        case 'create':
                            $action = 'createAction';
                            break;

                        case 'update':
                            $action = 'updateAction';
                            break;

                        case 'delete':
                            $action = 'deleteAction';
                            break;

                        default:
                            throw new Exception('Метод не найден.');
                            break;
                    }
                } else {
                    throw new Exception('Метод не указан.');
                }
                break;
        }
        return $action;
    }

    abstract protected function indexAction();

    abstract protected function viewAction();

    abstract protected function createAction();

    abstract protected function updateAction();

    abstract protected function deleteAction();
}
