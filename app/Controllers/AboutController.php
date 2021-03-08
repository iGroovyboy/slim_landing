<?php


namespace App\Controllers;


use App\Services\Config;

class AboutController extends BaseController
{

    public function default(): string
    {
        $someVar = Config::get('someVar');
        $someVar2 = Option::get('someVar');

        return View::render('about.html', ['some' => $someVar]);
    }

}