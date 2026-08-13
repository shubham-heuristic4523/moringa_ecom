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

    public function form($product = null)
    {
        return view('admin.form', ['productId' => $product]);
    }

    public function orders()
    {
        return view('admin.orders');
    }

    public function customers()
    {
        return view('admin.customers');
    }

    public function offers()
    {
        return view('admin.offers');
    }

    public function flashSales()
    {
        return view('admin.flash-sales');
    }

    public function referrals()
    {
        return view('admin.referrals');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function admins()
    {
        return view('admin.admins');
    }

    public function reports()
    {
        return view('admin.reports');
    }
}
