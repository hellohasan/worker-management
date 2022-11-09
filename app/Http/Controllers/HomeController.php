<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Http\Controllers\ChartJs\WorkerStatus;
use App\Http\Controllers\ChartJs\CountryWorkers;
use App\Http\Controllers\ChartJs\Last30DaysOrder;

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
        $data['page_title'] = 'Dashboard';
        $data['workersChart'] = (new CountryWorkers)->generate();
        $data['statusChart'] = (new WorkerStatus)->generate();
        $data['last30Days'] = (new Last30DaysOrder)->generate();
        $data['workers'] = Worker::with('user')->whereStatusId(1)->take(12)->get();
        return view('backend.dashboard', $data);
    }
}
