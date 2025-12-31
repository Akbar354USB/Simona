<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\GuestBook;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalPegawai = Employee::count();
        $totalTamu = GuestBook::count();
        $overallProgress = Categories::overallProgress();
        return view('dashboard',  compact('totalPegawai', 'totalTamu', 'overallProgress'));
    }
}
