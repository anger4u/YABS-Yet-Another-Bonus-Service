<?php

namespace Classes;

trait Helper
{
    private function requestStatus($code)
    {
        $status = array(
            200 => 'OK',
            403 => 'Not Found',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code]) ? $status[$code] : $status[500];
    }

    protected function response($data, $status = 500)
    {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        die(json_encode($data));
    }

    public function formattedPhoneNumber($number = '')
    {
        if ('' != $number) {
            // удаляем символы
            $number = str_replace(["+", "(", ")", "-", " "], "", $number);

            // переворачиваем номер
            $number = strrev($number);

            // выбираем первые 10 символов
            $number = substr($number, 0, 10);

            // переворачиваем
            $number = strrev($number);

            // добавляем 8 к полученному результату
            $number = "8$number";
        }

        return $number;
    }
}