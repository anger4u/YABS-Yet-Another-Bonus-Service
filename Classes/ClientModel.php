<?php

namespace Classes;

//use Classes\Database;
//use Classes\Helper;
use Exception;

class ClientModel
{
    use Helper;

    private $table = 'clients';

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
    public function getClient($options = [])
    {
        extract(array_merge([
            'id' => null,
            'phone' => null,
        ], $options));

        try {
            if (empty($id) && empty($phone)) {
                throw new Exception('Необходимо передать один из параметров', 405);
            }

            $query = [];

            if ($id) {
                $query = $this->db->getAll("SELECT * FROM $this->table WHERE id = ?s", $id);
            } else if ($phone) {
                $query = $this->db->getAll("SELECT * FROM $this->table WHERE phone = ?s", $this->formattedPhoneNumber($phone));
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
    public function getClients()
    {
        return $this->db->getAll("SELECT * FROM $this->table");
    }

    /**
     * Создание клиента
     *
     * @param array $options
     */
    public function createClient($options = []): array
    {
        $defaults = [
            'name' => null,
            'surname' => null,
            'phone' => null,
            'birthday' => null,
        ];

        $options = array_merge($defaults, $options);

        extract($options);

        try {
            $anyIsEmpty = false;

            foreach ($defaults as $key => $value) {
                if (empty($options[$key])) {
                    $anyIsEmpty = true;
                }
            }

            if (true === $anyIsEmpty) {
                throw new Exception('Не передан один из параметров', 405);
            }

            $phone = $this->formattedPhoneNumber($phone);

            if ($this->getClient(['phone' => $phone])) {
                throw new Exception('Такой клиент уже существует', 405);
            }

            $options['birthday'] = date('Y-m-d', strtotime($options['birthday']));

            try {
                $buildSet = "";

                foreach ($defaults as $key => $value) {
                    $buildSet .= $key . "='" . $options[$key] . "', ";
                }

                $buildSet = substr($buildSet, 0, -2);

                $this->db->query("INSERT INTO $this->table SET $buildSet");

                return $this->getClient(['phone' => $phone]);
            } catch (Exception $ex) {
                throw new Exception('Ошибка создания клиента', 405);
            }
        } catch (Exception $ex) {
            return $this->response(['error' => $ex->getMessage()], $ex->getCode());
        }
    }
}
