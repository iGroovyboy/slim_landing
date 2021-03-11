<?php


namespace App\Controllers;


class InstallController extends BaseController
{
    public function default(): string
    {
        return $this->view->renderTheme('install', 'install.html');
    }
}
