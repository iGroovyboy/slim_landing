<?php


namespace App\Controllers;


class EditController extends BaseController
{
    public function default()
    {
        return $this->view->render('home', ['isAdmin' => true, 'x-edit' => true]);
    }


}
