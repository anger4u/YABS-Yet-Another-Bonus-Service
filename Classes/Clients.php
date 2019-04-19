<?php

namespace Classes;

//use Classes\OperationModel;
//use Classes\UserModel;

class Clients extends Api
{
    // Вывод списка всех записей
    public function indexAction()
    {
        $clients = (new ClientModel)->getClients();

        return $this->response(['clients' => $clients], 200);
    }

    // Просмотр отдельного клиента (по id)
    public function viewAction()
    {
        //id должен быть первым параметром после /api/clients/:id
        $id = array_pop($this->requestPath);

        if ($id) {
            $client = (new ClientModel)->getClient(['id' => $id]);
            if ($client) {
                return $this->response(['client' => $client], 200);
            }
        }

        return $this->response(['error' => 'Клиент не найден'], 404);
    }

    // Создание нового клиента
    public function createAction()
    {
        $name = $this->requestParams['name'] ?? '';
        $surname = $this->requestParams['surname'] ?? '';
        $gender = $this->requestParams['gender'] ?? '';
        $phone = $this->requestParams['phone'] ?? '';
        $birthday = $this->requestParams['birthday'] ?? '';
        $discount_rate = $this->requestParams['discount_rate'] ?? '';
        $card_number = $this->requestParams['card_number'] ?? '';
        $status = $this->requestParams['status'] ?? '';

        (new ClientModel)->createClient([
            'name' => $name,
            'surname' => $surname,
            'gender' => $gender,
            'phone' => $phone,
            'birthday' => $birthday,
            'discount_rate' => $discount_rate,
            'card_number' => $card_number,
            'status' => $status,
        ]);

        // занесение данных о создании пользователя в историю операций
        $getClient = (new ClientModel)->getClient(['phone' => $phone]);

        if (array_key_exists('id', $getClient)) {

            $getUser = (new UserModel)->getUser(['login' => $_SERVER['PHP_AUTH_USER']]);

            (new OperationModel)->createOperation([
                "phone" => $getClient['phone'],
                "percent_change" => $getClient['discount_rate'],
                "status_change" => 1,
                "issued_by" => $getUser['id'],
                "operated_by" => $getUser['id'],
                "type" => "1"
            ]);

            return $this->response(['message' => 'Клинет создан'], 200);
        }

        return $this->response(['error' => "Ошибка создания клиента"], 500);
    }

    // Обновление отдельной записи (по ее id)
    public function updateAction()
    {
        $id = $this->requestParams['id'] ?? '';
        $name = $this->requestParams['name'] ?? '';
        $surname = $this->requestParams['surname'] ?? '';
        $gender = $this->requestParams['gender'] ?? '';
        $phone = $this->requestParams['phone'] ?? '';
        $birthday = $this->requestParams['birthday'] ?? '';
        $discount_rate = $this->requestParams['discount_rate'] ?? '';

        $client = new ClientModel;

        $updateClient = $client->updateClient([
            'id' => $id,
            'name' => $name,
            'surname' => $surname,
            'gender' => $gender,
            'phone' => $phone,
            'birthday' => $birthday,
            'discount_rate' => $discount_rate,
        ]);

        if ($updateClient) {
            return $this->response(['message' => 'Данные обновлены'], 200);
        }

        return $this->response(['error' => "Ошибка обновления данных"], 500);
    }

    // удаление клиента по идентификатору
    public function deleteAction()
    {
        $id = $this->requestParams['id'] ?? '';

        $client = new ClientModel;

        $deleteClient = $client->deleteClient([
            'id' => $id,
        ]);

        if ($deleteClient) {
            return $this->response(['message' => 'Клиент удалён'], 200);
        }

        return $this->response(['error' => "Ошибка удаления"], 500);
    }
}
