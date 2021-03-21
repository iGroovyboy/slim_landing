<?php


namespace App\Controllers;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class InstallController extends BaseController
{
    protected $data;
//    public function default(): string
//    {
//        return $this->view->render('install');
//    }


    public function render($data)
    {
        if ($data) {
            $this->data = $data;
            $this->install();
        }

        return $this->view->render('install');
    }

    protected function install()
    {
    }
}
