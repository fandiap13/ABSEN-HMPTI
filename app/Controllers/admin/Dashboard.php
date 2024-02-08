<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MemberModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $MemberModel = new MemberModel();
        $session_id = session("LoggedUserData")['nim'];
        $user = $MemberModel->getAnggota($session_id)->getRowArray();

        return view("admin/v_index", [
            'title' => 'Dashboard',
            'subtitle' => "",
            'user' => $user,
        ]);
    }
}
