<?php


namespace App\Controllers;


class AdminController extends BaseController
{
    public function default()
    {
        return $this->view->render('admin', ['secret' => "Only admin can see this!"]);
    }

}
