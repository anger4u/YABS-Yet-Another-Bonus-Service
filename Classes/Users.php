<?php

require_once 'Api.php';
require_once 'Database.php';
//require_once 'Users.php';

class Users extends Api
{
    public $apiName = 'Users';


    /**
     * Метод GET
     * Вывод списка всех записей
     * http://ДОМЕН/users
     * @return string
     */
    public function indexAction()
    {
        $db = (new Database())->getConnect();
        $query = 'SELECT * FROM users';
        $qRes = mysqli_query($db, $query);

        if($qRes){
            return $this->response($qRes, 200);
        }
        return $this->response('Данные не найдены', 404);
    }

    /**
     * Метод GET
     * Просмотр отдельной записи (по id)
     * http://ДОМЕН/users/1
     * @return string
     */
    public function viewAction()
    {
        echo 'test11';
        //id должен быть первым параметром после /users/x
        $id = array_shift($this->requestUri);

        if($id){
            $db = (new db())->getConnect();
            $user = Users::getById($db, $id);
            if($user){
                return $this->response($user, 200);
            }
        }
        return $this->response('Данные не найдены', 404);
    }

    /**
     * Метод POST
     * Создание новой записи
     * http://ДОМЕН/users + параметры запроса name, email
     * @return string
     */
    public function createAction()
    {
        $name = $this->requestParams['name'] ?? '';
        $email = $this->requestParams['email'] ?? '';
        if($name && $email){
            $db = (new db())->getConnect();
            $user = new Users($db, [
                'name' => $name,
                'email' => $email
            ]);
            if($user = $user->saveNew()){
                return $this->response('Data saved.', 200);
            }
        }
        return $this->response("Saving error", 500);
    }

    /**
     * Метод PUT
     * Обновление отдельной записи (по ее id)
     * http://ДОМЕН/users/1 + параметры запроса name, email
     * @return string
     */
    public function updateAction()
    {
        $parse_url = parse_url($this->requestUri[0]);
        $userId = $parse_url['path'] ?? null;

        $db = (new db())->getConnect();

        if(!$userId || !Users::getById($db, $userId)){
            return $this->response("User with id=$userId not found", 404);
        }

        $name = $this->requestParams['name'] ?? '';
        $email = $this->requestParams['email'] ?? '';

        if($name && $email){
            if($user = Users::update($db, $userId, $name, $email)){
                return $this->response('Data updated.', 200);
            }
        }
        return $this->response("Update error", 400);
    }

    /**
     * Метод DELETE
     * Удаление отдельной записи (по ее id)
     * http://ДОМЕН/users/1
     * @return string
     */
    public function deleteAction()
    {
        $parse_url = parse_url($this->requestUri[0]);
        $userId = $parse_url['path'] ?? null;

        $db = (new db())->getConnect();

        if(!$userId || !Users::getById($db, $userId)){
            return $this->response("User with id=$userId not found", 404);
        }
        if(Users::deleteById($db, $userId)){
            return $this->response('Data deleted.', 200);
        }
        return $this->response("Delete error", 500);
    }
}

new Users();