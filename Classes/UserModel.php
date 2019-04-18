<?php

namespace Classes;

//use Classes\Database;
//use Classes\Helper;
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

    /**
     * Получение пользователя
     *
     * @param array $options
     */
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

    /**
     * Вернуть список пользователей
     */
    public function getUsers()
    {
        return $this->db->getAll("SELECT * FROM $this->table");
    }

    /**
     * Создание пользователя
     *
     * @param array $options
     */
    public function createUser($options = [])
    {
        extract(array_merge([
            'login' => '',
            'password' => '',
        ], $options));

        try {
            if (empty($login) || empty($password)) {
                throw new Exception('Не передан один из параметров', 405);
            }

            if ($this->getUser(['login' => $login])) {
                throw new Exception('Такой пользователь уже существует', 405);
            }

            $password = password_hash($password, PASSWORD_DEFAULT);

            try {
                $this->db->query("INSERT INTO $this->table SET login = ?s, pass_hash = ?s", $login, $password);
                return true;
            } catch (Exception $ex) {
                throw new Exception('Ошибка создания пользователя', 405);
            }
        } catch (Exception $ex) {
            return $this->response(['error' => $ex->getMessage()], $ex->getCode());
        }
    }
}
