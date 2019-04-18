<?php

namespace Classes;

//use Classes\UserModel;
use Exception;

class Auth
{
    public $user;

    public function checkUser($options = [])
    {
        extract(array_merge([
            'login' => '',
            'password' => '',
        ], $options));

        if (empty($login) || empty($password)) {
            throw new Exception('Недостаточно данных', 405);
        }

        $userModel = new UserModel();

        $findUser = $userModel->getUser([
            'login' => $login,
        ]);

        if ($findUser) {
            if (password_verify($password, $findUser['pass_hash'])) {
                $this->user = $findUser;
            } else {
                throw new Exception('Неверный пароль', 405);
            }
        } else {
            throw new Exception('User is not found', 405);
        }
    }

    public function isOk()
    {
        if ($this->user) {
            return true;
        }
        return false;
    }
}
