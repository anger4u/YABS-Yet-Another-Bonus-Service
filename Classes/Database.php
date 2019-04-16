<?php

declare(strict_types=1);

class Database
{
    protected $host = 'localhost';
    protected $database = 'YABS_BONUS';
    protected $user = 'golf';
    protected $pswd = '';

    public function getConnect() {

        $dbh = mysqli_connect($this->host, $this->user, $this->pswd) or die("Не могу соединиться с MySQL.");
        $dbc = mysqli_select_db($dbh, $this->database) or die("Не могу подключиться к базе.");

        return $dbc;
    }
}