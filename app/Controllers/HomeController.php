<?php


namespace App\Controllers;


use App\Services\Config;

class HomeController extends BaseController
{
    public function default(): string
    {
        if ( ! Config::has('db', 'driver')) {
            return (new InstallController($this->container, $this->view))->default();
        }

        return $this->view->render('home.html');
    }
}