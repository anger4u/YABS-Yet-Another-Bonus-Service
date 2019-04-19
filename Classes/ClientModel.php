<?php

namespace Classes;

//use Classes\Helper;
use Exception;

class ClientModel
{
    use Helper;

    private $table = 'clients_cards';

    private $db;

    // подключение к базе
    public function __construct()
    {
        try {
            $this->db = new Database();
        } catch (Exception $e) {
            echo $this->response(['error' => 'Ошибка подключения к БД: ' . $e], 500);
        }
    }

    // Получение клиента по телефону или ид
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
                $query = $this->db->getAll("SELECT * FROM $this->table WHERE id=?s", $id);
            } else if ($phone) {
                $query = $this->db->getAll("SELECT * FROM $this->table WHERE phone=?s", $this->formattedPhoneNumber($phone));
            }

            if (count($query) > 0) {
                return $query[0];
            } else {
                return [];
            }
        } catch (Exception $ex) {
            return $this->response(['error' => $ex->getMessage()], $ex->getCode());
        }
    }

    // получение всех клиентов
    public function getClients()
    {
        return $this->db->getAll("SELECT * FROM $this->table");
    }

    // Создание клиента
    public function createClient($options = [])
    {
        $defaults = [
            'name' => null,
            'surname' => null,
            'gender' => null,
            'phone' => null,
            'birthday' => null,
            'discount_rate' => null,
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

            if (!is_numeric($discount_rate)) {
                throw new Exception('Неверный формат скидки', 405);
            }

            $options['discount_rate'] = floatval($discount_rate);

            if ($discount_rate > 7.5) {
                $options['discount_rate'] = 7.5;
            }

            if ($discount_rate < 1) {
                $options['discount_rate'] = 1;
            }

            if (true === $anyIsEmpty) {
                throw new Exception('Не передан один из параметров', 405);
            }

            $phone = $this->formattedPhoneNumber($phone);

            if ($this->getClient(['phone' => $phone])) {
                throw new Exception('Такой клиент уже существует', 405);
            }

            if (!$this->validateDate($options['birthday'], 'Y-m-d')) {
                throw new Exception('Неверный формат даты', 405);
            }

            $options['birthday'] = date('Y-m-d', strtotime($options['birthday']));

            $options['status'] = $defaults['status'] = 1;
            $options['card_number'] = $defaults['card_number'] = rand(100000, 900000);

            $buildSet = "";

            foreach ($defaults as $key => $value) {
                $buildSet .= $key . "='" . $options[$key] . "', ";
            }

            $buildSet = substr($buildSet, 0, -2);

            $this->db->query("INSERT INTO $this->table SET $buildSet");

            return true;
        } catch (Exception $ex) {
            return $this->response(['error' => $ex->getMessage()], $ex->getCode());
        }
    }

    // изменение данных клиента
    public function updateClient($options = [])
    {
        extract(array_merge([
            'id' => null,
            'name' => null,
            'surname' => null,
            'gender' => null,
            'phone' => null,
            'birthday' => null,
            'discount_rate' => null,
            'status' => null,
            'buy_total' => null,
            'discount_total' => null
        ], $options));

        try {
            if (!$id) {
                throw new Exception('Не указан id', 405);
            }

            if ($name) {
                $this->db->query("UPDATE $this->table SET name = ?s WHERE id=?i", $name, $id);
            }

            if ($surname) {
                $this->db->query("UPDATE $this->table SET surname = ?s WHERE id=?i", $surname, $id);
            }

            if ($gender) {
                $this->db->query("UPDATE $this->table SET gender = ?s WHERE id=?i", $gender, $id);
            }

            if ($phone) {
                $this->db->query("UPDATE $this->table SET phone = ?s WHERE id=?i", $phone, $id);
            }

            if ($birthday) {
                $this->db->query("UPDATE $this->table SET birthday = ?s WHERE id=?i", $birthday, $id);
            }

            if ($discount_rate) {
                $this->db->query("UPDATE $this->table SET discount_rate = ?s WHERE id=?i", $discount_rate, $id);
            }

            if ($buy_total) {
                $this->db->query("UPDATE $this->table SET buy_total = ?s WHERE id=?i", $buy_total, $id);
            }

            if ($discount_total) {
                $this->db->query("UPDATE $this->table SET discount_total = ?s WHERE id=?i", $discount_total, $id);
            }

            if (0 == $status || 1 == $status) {
                $this->db->query("UPDATE $this->table SET status = ?i WHERE id=?i", $status, $id);
            }

            return true;
        } catch (Exception $ex) {
            return $this->response(['error' => $ex->getMessage()], $ex->getCode());
        }
    }

    // удаление клиента
    public function deleteClient($options = [])
    {
        extract(array_merge([
            'id' => '',
        ], $options));

        try {
            if (empty($id)) {
                throw new Exception('Не передан один из параметров', 405);
            }

            if (!$this->getClient(['id' => $id])) {
                throw new Exception('Такого клиента не существует', 405);
            }

            try {
                $this->db->query("DELETE FROM $this->table WHERE id=?s", $id);
                return true;
            } catch (Exception $ex) {
                throw new Exception('Ошибка удаления клиента', 405);
            }
        } catch (Exception $ex) {
            return $this->response(['error' => $ex->getMessage()], $ex->getCode());
        }
    }
}
