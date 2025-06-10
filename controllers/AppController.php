<?php

namespace Controllers;

use MVC\Router;

class AppController {
    public static function index(Router $router){
        $router->render('login/index', [], $layout = 'layout/layoutlogin');
        //renderizar para la pagina principal, para que aparezaca
    }
}

