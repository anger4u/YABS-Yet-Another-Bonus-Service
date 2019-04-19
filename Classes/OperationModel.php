<?php

namespace Classes;

//use Classes\ClientModel;
//use Classes\Helper;
//use Classes\UserModel;
use Exception;

class OperationModel
{
    use Helper;

    private $table = 'clients_cards_operations';

    private $db;

    public function __construct()
    {
        try {
            $this->db = new Database();
        } catch (Exception $e) {
            echo $this->response(['error' => 'Ошибка подключения к БД: ' . $e], 500);
        }
    }

    // создание операции
    public function createOperation($options = [])
    {
        extract(array_merge([
            "phone" => '',
            "buy_sum" => '',
            "percent_change" => '',
            "status_change" => '',
            "type" => '',
        ], $options));

        try {

            try {
                $findCard = (new ClientModel)->getClient(['phone' => $this->formattedPhoneNumber($phone)]);
            } catch (Exception $ex) {
                throw new Exception('Создание операции: клиент не найден', 405);
            }

            try {
                $findUser = (new UserModel)->getUser(['login' => $_SERVER['PHP_AUTH_USER']]);
            } catch (Exception $ex) {
                throw new Exception('Создание операции: клиент не найден', 405);
            }

            $operated_by = $findUser['id'];
            $issued_by = $findUser['id'];

            $operation_type = "";

            if ('1' == $type) {
                $operation_type = 'Создание карты';
                $this->db->query("INSERT INTO $this->table SET card_id='" . $findCard['id'] . "', percent_change='$percent_change', status_change='$status_change', issued_by='$issued_by', operated_by='$operated_by', operation_type='$operation_type'");
            }

            if ('2' == $type) {
                $operation_type = 'Покупка';

                $getDiscountRules = $this->db->getAll("SELECT * FROM discount_rules");

                $findCard['buy_total'] = floatval($findCard['buy_total']) + floatval($buy_sum);

                foreach ($getDiscountRules as $rule) {
                    if ($findCard['buy_total'] >= $rule['checksum']) {
                        $findCard['discount_rate'] = $rule['bonus_rate'];
                    }
                }

                $getHolidays = $this->db->getAll("SELECT * FROM holidays_list");

                $nowDate = date('d-m');
                $isHoliday = false;

                foreach ($getHolidays as $holiday) {
                    if ($nowDate == $holiday['holiday_date']) {
                        $operation_type = 'Покупка в праздничный день: ' . $holiday['holiday_name'];
                        $isHoliday = true;
                    }
                }

                $rest_buy_sum = ((floatval($buy_sum) * ($isHoliday ? ($findCard['discount_rate'] * 2) : $findCard['discount_rate'])) / 100);

                (new ClientModel)->updateClient([
                    'id' => $findCard['id'],
                    'discount_rate' => $findCard['discount_rate'],
                    'buy_total' => $findCard['buy_total'],
                    'status' => $findCard['status'],
                    'discount_total' => floatval($findCard['discount_total']) + $rest_buy_sum,
                ]);

                $this->db->query("INSERT INTO $this->table SET card_id='" . $findCard['id'] . "', buy_sum='$buy_sum', percent_change='" . ($isHoliday ? ($findCard['discount_rate'] * 2) : $findCard['discount_rate']) . "', issued_by='$issued_by', operated_by='$operated_by', operation_type='$operation_type'");
            }

            if ('3' == $type) {
                $operation_type = 'Изменение статуса';
                $this->db->query("INSERT INTO $this->table SET card_id='" . $findCard['id'] . "', status_change='" . (0 == $findCard['status'] ? 1 : 0) . "', operated_by='$operated_by', operation_type='$operation_type'");
                (new ClientModel)->updateClient([
                    'id' => $findCard['id'],
                    'status' => 0 == $findCard['status'] ? 1 : 0,
                ]);
            }

            return true;
        } catch (Exception $ex) {
            return $this->response(['error' => $ex->getMessage()], $ex->getCode());
        }
    }

    // получение списка всех операций
    public function getOperations()
    {
        return $this->db->getAll("SELECT * FROM $this->table");
    }
}
