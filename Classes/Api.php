<?php

declare(strict_types=1);

abstract class Api
{
    public $apiName = ''; // Название API

    protected $method = ''; // Метод запроса

    protected $uriData = []; // url параметры

    protected $action = ''; //Название метода для выполнения

    public function __construct()
    {
        // Определение метода запроса
        $this->method = $_SERVER['REQUEST_METHOD'];
        print_r($this->method);

        // Определение параметров
        $this->uriData = array_slice(explode('/', rtrim($_GET['req'], '/')), 1);

        //Определение действия для обработки
        $this->action = $this->getAction();

        // Вызов действия
        $this->{$this->action}();
    }

    protected function getAction()
    {
        $method = $this->method;
        switch ($method) {
            case 'GET':
                if ($this->uriData) {
                    return 'viewAction';
                } else {
                    return 'indexAction';
                }
                break;
            case 'POST':
                if($this->uriData[0] === 'create') {
                    return 'createAction';
                } elseif ($this->uriData[0] === 'update') {
                    return 'updateAction';
                } elseif ($this->uriData[0] === 'delete') {
                    return 'deleteAction';
                } else {
                    throw new Exception('Метод не указан.');
                }
                break;
            default:
                return null;
        }
    }

    private function requestStatus($code)
    {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code]) ? $status[$code] : $status[500];
    }

    //
    protected function response($data, $status = 500)
    {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        return json_encode($data);
    }

    abstract protected function indexAction();

    abstract protected function viewAction();

    abstract protected function createAction();

    abstract protected function updateAction();

    abstract protected function deleteAction();
}