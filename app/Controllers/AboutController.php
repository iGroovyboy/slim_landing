<?php


namespace App\Controllers;


use App\Models\DB\Option;
use App\Services\Config;
use App\Models\Node;
use App\Services\DB\DB;
use App\Services\View;
use Psr\Container\ContainerInterface;

class AboutController extends BaseController
{

    protected const TABLE_NAMEX = 'protected nodesxxxxx';
    public const TABLE_NAMEx = 'nodesxxxxx';

    public function __construct(ContainerInterface $container, View $view)
    {
        parent::__construct($container, $view);
    }

    public function default(): string
    {
//        $someVar = Config::get('someVar');
//
//        $someVar2 = Option::get('someVar');
//
//        $data = DB::query("SELECT * ...");

        Node::of('Page', 3)->getAll('element');
//        Node::of('Page', 3)->get('element', 'li');
//        Node::of('Page', 12)->get('element', '123');
//
//        Node::of('Page', 12)->update('element', 'hero', 'value');
//
//        Node::of('Page', 12)->create('element', 'hero', 'value');
//
//        Node::of('Page', 12)->deleteAll('element');
//        Node::of('Page', 12)->delete('element', 'header');
//        Node::of('Page', 12)->delete('element', ['footer', 'body']);


//        return View::render('about.html', ['some' => $someVar]);
    }

}