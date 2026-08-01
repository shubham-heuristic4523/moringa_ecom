<?php

namespace App\Http\Controllers\Admin\View;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    //
    public function index()
    {
        return view('welcome');
    }

    public function list()
    {
        return view('admin.list');
    }

    public function form()
    {
        return view('admin.form');
    }
}
