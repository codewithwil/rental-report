<?php

namespace App\Http\Controllers\API\Dashboard;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Branch\Branch,
    Models\User
};
use App\Models\Resources\Rules\Rules;

class DashboardC extends Controller
{
    public function index(){
        $users         = User::count();
        $branch        = Branch::count();
        $rules         = Rules::first();
        return view('admin.dashboard.index', [
            'users'   => $users,
            'branch'  => $branch,
            'rules'   => $rules,
        ]);   
    }
}
