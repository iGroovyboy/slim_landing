<?php


namespace App\Controllers;


use App\Models\Node;

class EditController extends BaseController
{
    public function default()
    {
        $data = Node::getAllFor('home');

        return $this->view->render('home', [
            'slug' => 'home',
            'isAdmin' => true,
            'x-edit' => true,
            'data' => $data['home']['items']
        ]);
    }


}
