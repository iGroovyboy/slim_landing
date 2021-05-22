<?php


namespace App\Controllers;


use App\Models\Node;
use App\Models\User;
use App\Services\Config;

final class HomeController extends BaseController
{
    public function default(): string
    {
        try {
            Config::has('db/driver');
        } catch (\Symfony\Component\PropertyAccess\Exception\NoSuchIndexException $e) {
            return (new InstallController($this->container, $this->view))
                ->render($this->request->getParsedBody());
        }

        if ( ! User::hasAny()) {
            return (new InstallController($this->container, $this->view))
                ->render($this->request->getParsedBody());
        }

        $data = Node::getAllFor('home');

        return $this->view->render('home', ['slug' => 'home', 'asd'=>'345', 'data' => $data['home']['items']]);
    }
}
