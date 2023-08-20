<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $totalBalance = 0;

        $wallets = [];
        $transactions = [];

        return view('dashboard');
    }
}
