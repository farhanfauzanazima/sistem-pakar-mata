<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class BerandaController extends Controller
{
    public function index()
    {
        return view('user.beranda.index');
    }
}