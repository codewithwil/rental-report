<?php

namespace App\Http\Controllers\API\People\Admin;

use App\{
    Http\Controllers\Controller,
    Models\People\Admin\Admin,
    Models\Resources\Company\Company,
    Models\User,
    Traits\DbBeginTransac
};

use Illuminate\{
    Http\Request,
    Support\Facades\Auth,
    Support\Facades\Hash,
    Support\Facades\Storage,
    Support\Facades\Validator
};

use Spatie\Permission\Models\Role;

class AdminC extends Controller
{
    use DbBeginTransac;

    public function index()
    {
        if (Auth::user()->hasRole(['admin', 'owner']) && Auth::user()->branch_id === null) {
            $users = Admin::with('user.branch')->get();
        } else {
            $users = Admin::whereHas('user', function ($query) {
                $query->where('branch_id', Auth::user()->branch_id);
            })->with('user.branch')->get();
        }

        return view('admin.people.admin.index', compact('users'));
    }
    

    public function create(){
        $roles    = Role::all(); 
        return view('admin.people.admin.create', compact('roles'));
    }

    public function invoice(){
        $users   = Admin::all();
        $company = Company::first();
        return view('admin.people.admin.invoice', compact('users', 'company'));
    }

    public function edit($adminId)
    {
        $users = Admin::with('user')->findOrFail($adminId);
        $roles = Role::all(); 
        $userRole = $users->user->getRoleNames()->first(); 
    
        return view('admin.people.admin.update', compact('users', 'roles', 'userRole'));
    }
    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name'     => 'required|string|max:255',
            'telepon'  => 'required|numeric',
            'role'     => 'required|in:admin,supervisor,petugas,owner,pengguna',
            'foto'     => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            return $this->executeTransaction(function () use ($request) {
                $user = User::create([
                    'email'     => $request->input('email'),
                    'password'  => Hash::make($request->input('password')),
                    'branch_id' => null,
                ]);

                $user->assignRole($request->input('role'));

                $fotoPath = null;
                if ($request->hasFile('foto')) {
                    $fotoPath = $request->file('foto')->store('admin_foto', 'public');
                }

                Admin::create([
                    'user_id' => $user->id,
                    'name'    => $request->input('name'),
                    'telepon' => $request->input('telepon'),
                    'foto'    => $fotoPath,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Data admin berhasil ditambahkan.',
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $adminId)
    {
        $request->validate([
            'email'     => 'nullable|email',
            'name'      => 'nullable|string|max:255',
            'telepon'   => 'nullable|digits_between:10,15',
            'password'  => 'nullable|min:8',
            'role'      => 'nullable|exists:roles,name',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            return $this->executeTransaction(function () use ($request, $adminId) {
                $admin = Admin::findOrFail($adminId);
                $user = $admin->user;

                if ($request->filled('email')) {
                    $user->email = $request->email;
                }
                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }
                $user->save();

                if ($request->filled('role')) {
                    $user->syncRoles($request->role);
                }

                if ($request->hasFile('foto')) {
                    if ($admin->foto && Storage::exists('public/' . $admin->foto)) {
                        Storage::delete('public/' . $admin->foto);
                    }

                    $admin->foto = $request->file('foto')->store('admin_foto', 'public');
                }

                $admin->name = $request->name;
                $admin->telepon = $request->telepon;
                $admin->save();

                return redirect('/people/admin')->with('success', 'Data user berhasil diperbarui.');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
  
    
    public function delete($adminId)
    {
        try {
            return $this->executeTransaction(function () use ($adminId) {
                $admin = Admin::findOrFail($adminId);
                $user = $admin->user;

                $user->roles()->detach();

                if ($admin->foto && Storage::exists('public/' . $admin->foto)) {
                    Storage::delete('public/' . $admin->foto);
                }

                $admin->delete();
                $user->delete();

                return redirect('/people/admin')->with('success', 'Data user berhasil dihapus beserta peran-perannya');
            });
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/people/admin')->with('error', 'Admin tidak ditemukan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
