<?php

namespace Classes;

//use Classes\Api;
//use Classes\OperationModel;

class Operations extends Api
{
    // получение списка всех операций
    public function indexAction()
    {
        $operations = (new OperationModel)->getOperations();

        return $this->response(['operations' => $operations], 200);
    }


    // создание операции
    public function createAction()
    {
        $type = $this->requestParams['type'] ?? '';
        $phone = $this->requestParams['phone'] ?? '';
        $buy_sum = $this->requestParams['buy_sum'] ?? '';
        $percent_change = $this->requestParams['percent_change'] ?? '';
        $status_change = $this->requestParams['status_change'] ?? '';

        $createOperation = (new OperationModel)->createOperation([
            "type" => $type,
            "phone" => $phone,
            "buy_sum" => $buy_sum,
            "percent_change" => $percent_change,
            "status_change" => $status_change,
        ]);

        if ($createOperation) {
            return $this->response(['message' => 'Операция создана'], 200);
        }

        return $this->response(['error' => "Ошибка создания операции"], 500);
    }

    protected function viewAction()
    {

    }

    protected function updateAction()
    {

    }

    protected function deleteAction()
    {

    }
}
