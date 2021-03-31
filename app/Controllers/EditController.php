<?php


namespace App\Controllers;


class EditController extends BaseController
{
    public function default()
    {
        return $this->view->render('edit', ['secret' => "Only admin can see this!"]);
    }


}
