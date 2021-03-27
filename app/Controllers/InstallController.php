<?php


namespace App\Controllers;


use App\Models\User;
use App\Services\DB\DB;

class InstallController extends BaseController
{
    protected $data;

    public function render($data)
    {
        if ($data) {
            $this->data = $data;
            $this->install();
        }

        global $app;
        $routeParser =  $app->getRouteCollector()->getRouteParser();
        $loginUrl = $routeParser->urlFor('login');

        $hasDb = DB::isConnected();
        $hasUser = $hasDb ? User::hasAny() : false;

        $vars = [
            'hasDb' => $hasDb,
            'hasUser' => $hasUser,
            'loginUrl' => $loginUrl,
        ];

        return $this->view->render('install', $vars);
    }

    protected function install()
    {
    }
}
