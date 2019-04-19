<?php

namespace Classes;

use Exception;

class UserModel
{
    use Helper;

    private $table = 'users';

    private $db;

    public function __construct()
    {
        try {
            $this->db = new Database();
        } catch (Exception $e) {
            echo $this->response(['error' => 'Ошибка подключения к БД: ' . $e], 500);
        }
    }

    // Получение пользователя по логину или идентификатору
    public function getUser($options = [])
    {
        extract(array_merge([
            'login' => '',
            'id' => '',
        ], $options));

        try {
            if (empty($login) && empty($id)) {
                throw new Exception('Необходимо передать один из параметров', 405);
            }

            $query = [];

            if ($login) {
                $query = $this->db->getAll("SELECT * FROM $this->table WHERE login = ?s", $login);
            } else if ($id) {
                $query = $this->db->getAll("SELECT * FROM $this->table WHERE id = ?i", (int)$id);
            }

            if (count($query) > 0) {
                return $query[0];
            } else {
                return false;
            }
        } catch (Exception $ex) {
            return $this->response(['error' => $ex->getMessage()], $ex->getCode());
        }
    }

    // получения списка всех пользователей
    public function getUsers()
    {
        return $this->db->getAll("SELECT * FROM $this->table");
    }

    // создание пользователя
    public function createUser($options = [])
    {
        extract(array_merge([
            'login' => '',
            'password' => '',
            'position' => ''
        ], $options));

        try {
            if (empty($login) || empty($password) || empty($position)) {
                throw new Exception('Не передан один из параметров', 405);
            }

            if ($this->getUser(['login' => $_SERVER['PHP_AUTH_USER']])['login'] !== 'master') {
                throw new Exception('Создавать пользователей может только мастер запись', 405);
            }

            if ($this->getUser(['login' => $login])) {
                throw new Exception('Такой пользователь уже существует', 405);
            }

            $password = password_hash($password, PASSWORD_DEFAULT);

            try {
                $this->db->query("INSERT INTO $this->table SET login = ?s, pass_hash = ?s, position = ?s", $login, $password, $position);
                return true;
            } catch (Exception $ex) {
                throw new Exception('Ошибка создания пользователя', 405);
            }
        } catch (Exception $ex) {
            return $this->response(['error' => $ex->getMessage()], $ex->getCode());
        }
    }

    // изменение данных пользователя по идентификатору
    public function updateUser($options = [])
    {
        extract(array_merge([
            'id' => '',
            'login' => '',
            'password' => '',
            'position' => ''
        ], $options));

        try {
            if (empty($id)) {
                throw new Exception('Не указан id', 405);
            } elseif (empty($login) && empty($password) && empty($position)) {
                throw new Exception('Нечего менять', 405);
            }

            if ($this->getUser(['login' => $_SERVER['PHP_AUTH_USER']])['login'] !== 'master') {
                throw new Exception('Изменять пользователей может только мастер запись', 405);
            }

            if (!empty($login)) {
                $this->db->query("UPDATE $this->table SET login = ?s WHERE id=?i", $login, $id);
            }

            if (!empty($password)) {
                $password = password_hash($password, PASSWORD_DEFAULT);
                $this->db->query("UPDATE $this->table SET pass_hash = ?s WHERE id=?i", $password, $id);
            }

            if (!empty($position)) {
                $this->db->query("UPDATE $this->table SET position = ?s WHERE id=?i", $position, $id);
            }
            return true;
        } catch (Exception $ex) {
            return $this->response(['error' => $ex->getMessage()], $ex->getCode());
        }
    }

    // удаление пользователя
    public function deleteUser($options = [])
    {
        extract(array_merge([
            'login' => ''
        ], $options));

        try {
            if (empty($login)) {
                throw new Exception('Не передан один из параметров', 405);
            }

            if ($this->getUser(['login' => $_SERVER['PHP_AUTH_USER']])['login'] !== 'master') {
                throw new Exception('Удалять пользователей может только мастер запись', 405);
            }

            if (!$this->getUser(['login' => $login])) {
                throw new Exception('Такого пользователя не существует', 405);
            }

            try {
                $this->db->query("DELETE FROM $this->table WHERE login=?s", $login);
                return true;
            } catch (Exception $ex) {
                throw new Exception('Ошибка удаления пользователя', 405);
            }
        } catch (Exception $ex) {
            return $this->response(['error' => $ex->getMessage()], $ex->getCode());
        }
    }
}

