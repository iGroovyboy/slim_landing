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

        $data = Node::of('home')->get();

        $gallery = [
            (object)[
                'src' => 'assets/images/work/w-1.jpg',
                'title' => 'adasd',
                'href' => 'assets/images/work/w-1.jpg',
            ],
            (object)[
                'src' => 'assets/images/work/w-2.jpg',
                'title' => 'gd dfg rerge',
                'href' => 'assets/images/work/w-2.jpg',
            ],
            (object)[
                'src' => 'assets/images/work/w-3.jpg',
                'title' => 'fcgxxg gxxg',
                'href' => 'assets/images/work/w-3.jpg',
            ],
        ];

        return $this->view->render('home', [
            'slug' => 'home',
            'gallery' => $gallery,
            //'data' => $data['home']['items']
        ] + $data['home']['items'] );
    }
}
