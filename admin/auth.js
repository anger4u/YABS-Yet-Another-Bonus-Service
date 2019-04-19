+(function ($) {

    'use strict';

    // class YABS {
    //     public $authForm;
    //     public $login;
    //     public function __construct() {
    //         $this->login = $('#input_login');
    //     }
    // }

    // конструктор прототипа
    var YABS = function () {
        this.$authForm = $('#auth_form')
        this.$login = $('#input_login')
        this.$password = $('#input_password')
        this.$users_dump = $('#users_dump')
        this.$clients_dump = $('#clients_dump')
        this.$operations_dump = $('#operations_dump')
    }

    // Метод для отправки ajax запроса
    YABS.prototype.sendAjax = function (options = {}) {
        return $.ajax({
            url: options.url,
            method: options.method || 'GET',
            dataType: "json",
            data: options.data || {},
            headers: {
                'Authorization': "Basic " + btoa(this.$login.val() + ":" + this.$password.val()),
                'contentType': 'application/json;charset=UTF-8'
            },
            error: function () {
                console.log('error');
            }
        })
    }

    // метод получения пользователей
    YABS.prototype.getUsersList = function () {
        var self = this
        this.sendAjax({
            url: "/api/users",
        }).done(function (response) {
            self.$users_dump.html(JSON.stringify(response.error ? response.error : response.users, null, 4))
        })
    }

    // метод получения клиентов
    YABS.prototype.getClientsList = function () {
        var self = this
        this.sendAjax({
            url: "/api/clients",
        }).done(function (response) {
            self.$clients_dump.html(JSON.stringify(response.error ? response.error : response.clients, null, 4))
        })
    }

    // метод получения Операций
    YABS.prototype.getOperationsList = function () {
        var self = this
        this.sendAjax({
            url: "/api/operations",
        }).done(function (response) {
            self.$operations_dump.html(JSON.stringify(response.error ? response.error : response.operations, null, 4))
        })
    }

    $(document).ready(function () {
        $.yabs = new YABS()
    })

})(window.jQuery);