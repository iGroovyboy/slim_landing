<?php


namespace App\Controllers;


use App\Services\Config;

class AboutController extends BaseController
{

    public function default(): string
    {
        $someVar = Config::get('someVar');

        $someVar2 = Option::get('someVar');
        $data = DB::query("SELECT * ...");

        Node::of('Page', 3)->getAll('element');
        Node::of('Page', 3)->get('element', 'li');
        Node::of('Page', 12)->get('element', '123');

        Node::of('Page', 12)->update('element', 'hero', 'value');

        Node::of('Page', 12)->deleteAll('element');
        Node::of('Page', 12)->delete('element', 'header');
        Node::of('Page', 12)->delete('element', ['footer', 'body']);


        return View::render('about.html', ['some' => $someVar]);
    }

}