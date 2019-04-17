<?php

namespace Classes;

use Classes\Api;
use Classes\ClientModel;

class Clients extends Api
{
    /**
     * Метод GET
     * Вывод списка всех записей
     * http://ДОМЕН/clients
     * @return string
     */
    public function indexAction()
    {
        $clients = (new ClientModel)->getClients();

        return $this->response(['clients' => $clients], 200);
    }

    /**
     * Метод GET
     * Просмотр отдельной записи (по id)
     * http://ДОМЕН/clients/1
     * @return string
     */
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

    /**
     * Метод POST
     * Создание новой записи
     * http://ДОМЕН/users + параметры запроса name, email
     * @return string
     */
    public function createAction()
    {
        $name     = $this->requestParams['name'] ?? '';
        $surname  = $this->requestParams['surname'] ?? '';
        $phone    = $this->requestParams['phone'] ?? '';
        $birthday = $this->requestParams['birthday'] ?? '';

        $client = new ClientModel;

        $createClient = $client->createClient([
            'name'     => $name,
            'surname'  => $surname,
            'phone'    => $phone,
            'birthday' => $birthday,
        ]);

        if (array_key_exists('id', $createClient)) {
            return $this->response(['message' => 'Клинет создан'], 200);
        }

        return $this->response(['error' => "Ошибка создания клиента"], 500);
    }

    /**
     * Метод PUT
     * Обновление отдельной записи (по ее id)
     * http://ДОМЕН/users/1 + параметры запроса name, email
     * @return string
     */
    public function updateAction()
    {
        // $parse_url = parse_url($this->requestUri[0]);
        // $userId    = $parse_url['path'] ?? null;

        // $db = (new db())->getConnect();

        // if (!$userId || !Users::getById($db, $userId)) {
        //     return $this->response("User with id=$userId not found", 404);
        // }

        // $name  = $this->requestParams['name'] ?? '';
        // $email = $this->requestParams['email'] ?? '';

        // if ($name && $email) {
        //     if ($user = Users::update($db, $userId, $name, $email)) {
        //         return $this->response('Data updated.', 200);
        //     }
        // }
        // return $this->response("Update error", 400);
    }

    /**
     * Метод DELETE
     * Удаление отдельной записи (по ее id)
     * http://ДОМЕН/users/1
     * @return string
     */
    public function deleteAction()
    {
        // $parse_url = parse_url($this->requestUri[0]);
        // $userId    = $parse_url['path'] ?? null;

        // $db = (new db())->getConnect();

        // if (!$userId || !Users::getById($db, $userId)) {
        //     return $this->response("User with id=$userId not found", 404);
        // }
        // if (Users::deleteById($db, $userId)) {
        //     return $this->response('Data deleted.', 200);
        // }
        // return $this->response("Delete error", 500);
    }
}
