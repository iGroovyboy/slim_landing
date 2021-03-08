<?php


namespace App\Controllers;

use App\Services\View;
use Psr\Http\Message\ResponseInterface;

class HelpController extends BaseController
{

    public function default(): string
    {



        return $this->view->render('help.html', ['go' => "HELP2+ world!"]);
    }
}