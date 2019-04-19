<?php

namespace Classes;

class Users extends Api
{
    // Вывод списка всех записей пользователей системы
    public function indexAction()
    {
        $users = (new UserModel)->getUsers();

        return $this->response(['users' => $users], 200);
    }

    // Просмотр отдельной записи
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

    // Создание новой записи
    public function createAction()
    {
        $login = $this->requestParams['login'] ?? '';
        $password = $this->requestParams['password'] ?? '';
        $position = $this->requestParams['position'] ?? '';

        $user = new UserModel;

        $createUser = $user->createUser([
            'login' => $login,
            'password' => $password,
            'position' => $position
        ]);

        if ($createUser) {
            return $this->response(['message' => 'Пользователь создан'], 200);
        }

        return $this->response(['error' => "Ошибка создания пользователя"], 500);
    }

    // Обновление записи записи
    public function updateAction()
    {
        $id = $this->requestParams['id'] ?? '';
        $login = $this->requestParams['login'] ?? '';
        $password = $this->requestParams['password'] ?? '';
        $position = $this->requestParams['position'] ?? '';

        $user = new UserModel;

        $updateUser = $user->updateUser([
            'id' => $id,
            'login' => $login,
            'password' => $password,
            'position' => $position
        ]);

        if ($updateUser) {
            return $this->response(['message' => 'Данные обновлены'], 200);
        }

        return $this->response(['error' => "Ошибка обновления данных"], 500);
    }

    // Удаление отдельной записи
    public function deleteAction()
    {
        $login = $this->requestParams['login'] ?? '';

        $user = new UserModel;

        $deleteUser = $user->deleteUser([
            'login' => $login
        ]);

        if ($deleteUser) {
            return $this->response(['message' => 'Пользователь удалён'], 200);
        }

        return $this->response(['error' => "Ошибка удаления пользователя"], 500);
    }
}
