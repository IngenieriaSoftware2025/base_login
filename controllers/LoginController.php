<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class LoginController {
    public static function renderizarPagina(Router $router) {

        $router->render('login/index', [], $layout = 'layout/layoutlogin');
    }


}
