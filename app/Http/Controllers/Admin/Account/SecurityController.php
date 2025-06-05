<?php

namespace App\Http\Controllers\Admin\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SecurityController extends Controller
{
    public function __construct()
    {
        $this->authorize("isAdmin");
    }

    public function index()
    {
        return view('admin.account.security', [
            'title' => 'Akun - Keamanan',
            'header_title' => 'Pengaturan Akun',
        ]);
    }
}
