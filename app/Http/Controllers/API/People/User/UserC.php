<?php

namespace App\Http\Controllers\API\People\User;

use App\{
    Http\Controllers\Controller,
    Models\User,
    Models\Resources\Branch\Branch,
    Models\Resources\Company\Company
};

use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Hash,
    Support\Facades\Validator,
    Support\Facades\Auth
};

use Spatie\Permission\Models\Role;

class UserC extends Controller
{
  
    public function index()
    {
        $branch = Branch::all();
        if (Auth::user()->hasRole(['admin', 'owner']) && Auth::user()->branch_id === null) {
            $users = User::with('branch')->get();
        } else {
            $users = User::where('branch_id', Auth::user()->branch_id)->get();
        }
        return view('admin.users.index', compact('users', 'branch'));
    }

    public function invoice(){
        $users   = User::all();
        $company = Company::first();
        return view('admin.users.invoice', compact('users', 'company'));
    }
    
}
