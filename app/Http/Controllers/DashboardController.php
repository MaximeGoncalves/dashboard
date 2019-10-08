<?php

namespace App\Http\Controllers;

use App\Technician;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index () {
        return view('layouts/master');
    }

}
