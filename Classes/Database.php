<?php

class Database
{
    protected $host = '';
    protected $database = '';
    protected $user = '';
    protected $pswd = '';

    public function getConnect() {
        $this->host = 'localhost'; // имя хоста
        $this->database = 'YABS_BONUS'; // имя базы данных
        $this->user = 'golf'; // имя пользователя
        $this->pswd = ''; // пароль

        $dbh = mysqli_connect($this->host, $this->user, $this->pswd) or die("Не могу соединиться с MySQL. ");
        mysqli_select_db($dbh, $this->database) or die("Не могу подключиться к базе. ");
    }

}