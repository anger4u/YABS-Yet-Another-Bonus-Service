<?php

namespace Classes;

//use Classes\Api;
//use Classes\UserModel;

class Users extends Api
{
    /**
     * Метод GET
     * Вывод списка всех записей
     * http://ДОМЕН/users
     *
     * @return string
     */
    public function indexAction()
    {
        $users = (new UserModel)->getUsers();

        return $this->response(['users' => $users], 200);
    }

    /**
     * Метод GET
     * Просмотр отдельной записи (по id)
     * http://ДОМЕН/users/1
     *
     * @return string
     */
    public function viewAction()
    {
        //id должен быть первым параметром после /api/users/:id
        $id = array_pop($this->requestPath);

        if ($id) {
            $user = (new UserModel)->getUser(['id' => $id]);
            if ($user) {
                return $this->response(['user' => $user], 200);
            }
        }

        return $this->response(['error' => 'Пользователь не найден'], 404);
    }

    /**
     * Метод POST
     * Создание новой записи
     * http://ДОМЕН/users + параметры запроса name, email
     *
     * @return string
     */
    public function createAction()
    {
        $login = $this->requestParams['login'] ?? '';
        $password = $this->requestParams['password'] ?? '';

        $user = new UserModel;

        $createUser = $user->createUser([
            'login' => $login,
            'password' => $password,
        ]);

        if ($createUser) {
            return $this->response(['message' => 'Пользователь создан'], 200);
        }

        return $this->response(['error' => "Ошибка создания пользователя"], 500);
    }

    /**
     * Метод PUT
     * Обновление отдельной записи (по ее id)
     * http://ДОМЕН/users/1 + параметры запроса name, email
     *
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
     *
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
