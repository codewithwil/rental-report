<?php

namespace App\Http\Controllers\API\Dashboard;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Branch\Branch,
    Models\User,
    Models\Resources\Rules\Rules,
    Models\Resources\Vehicle\Vehicle
};

class DashboardC extends Controller
{
    public function index(){
        $users    = User::count();
        $branch   = Branch::where('status', Branch::STATUS_ACTIVE)->count();
        $vehicle  = Vehicle::where('status', '!=', Vehicle::STATUS_DELETED)->count();
        $rules    = Rules::first();
        return view('admin.dashboard.index', [
            'users'   => $users,
            'branch'  => $branch,
            'rules'   => $rules,
            'vehicle' => $vehicle
        ]);   
    }
}
