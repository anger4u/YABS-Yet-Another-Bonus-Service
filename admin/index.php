<?php
//
//session_start();
//
//?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/admin/bower_components/bootstrap/dist/css/bootstrap.min.css" />
    <style>
        html,
        body {
            height: 100%;
        }

        .form-signin {
            width: 100%;
            max-width: 330px;
            padding: 15px;
            margin: auto;
        }

        .form-signin .checkbox {
            font-weight: 400;
        }

        .form-signin .form-control {
            position: relative;
            box-sizing: border-box;
            height: auto;
            padding: 10px;
            font-size: 16px;
        }

        .form-signin .form-control:focus {
            z-index: 2;
        }

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
    <script src="/admin/bower_components/jquery/dist/jquery.min.js"></script>
    <script src="/admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <script>
        <?=file_get_contents(dirname(__FILE__) . '/auth.js');?>
    </script>
</head>

<body class="text-center">
    <div class="container">
        <div class="row">
            <div class="col-3">
            <form id="auth_form" class="form-signin">
                <h1 class="h3 mb-3 font-weight-normal">Авторизация</h1>
                <input type="text" id="input_login" class="form-control" placeholder="Ваш логин" required autofocus value="master">
                <input type="password" id="input_password" class="form-control" placeholder="Ваш пароль" required value="master123">
            </form>
            </div>
            <div class="col-9">

                <ul class="nav nav-tabs mt-5" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="true">Сотрудники</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="clients-tab" data-toggle="tab" href="#clients" role="tab" aria-controls="clients" aria-selected="true">Клиенты</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="operations-tab" data-toggle="tab" href="#operations" role="tab" aria-controls="operations" aria-selected="true">Операции</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">

                    <div class="tab-pane fade show active" id="users" role="tabpanel" aria-labelledby="users-tab">
                        <div class="d-flex mt-3">
                            <button onclick="$.yabs.getUsersList()" class="btn btn-success">Загрузить список</button>
                        </div>
                        <pre class="container text-left mt-3" id="users_dump"></pre>
                    </div>

                    <div class="tab-pane fade show" id="clients" role="tabpanel" aria-labelledby="clients-tab">
                        <div class="d-flex mt-3">
                            <button onclick="$.yabs.getClientsList()" class="btn btn-success">Загрузить список</button>
                        </div>
                        <pre class="container text-left mt-3" id="clients_dump"></pre>
                    </div>

                    <div class="tab-pane fade show" id="operations" role="tabpanel" aria-labelledby="operations-tab">
                        <div class="d-flex mt-3">
                            <button onclick="$.yabs.getOperationsList()" class="btn btn-success">Загрузить список</button>
                        </div>
                        <pre class="container text-left mt-3" id="operations_dump"></pre>
                    </div>

                </div>

            </div>
        </div>
    </div>
</body>

</html>