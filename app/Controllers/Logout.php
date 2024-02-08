<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Logout extends BaseController
{
    public function index()
    {
        session()->remove('LoggedUserData');
        session()->remove('access_token');
        session()->setFlashdata('msg', 'success#Logout berhasil');
        return redirect()->to(base_url('login'));
    }
}
