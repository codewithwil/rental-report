<?php

namespace App\Http\Controllers\API\People\Admin;

use App\{
    Http\Controllers\Controller,
    Models\People\Admin\Admin,
    Models\Resources\Company\Company,
    Models\User,
    Traits\DbBeginTransac
};
use App\Models\History\ActivityLog\ActivityLog;
use Illuminate\{
    Http\Request,
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
        $users = Admin::select('adminId', 'user_id', 'foto', 'name', 'telepon') 
            ->with([
                'user' => function ($q) {
                    $q->select('id', 'email', 'branch_id') 
                    ->with('branch:branchId,address');   
                }
            ])
            ->get();

        return view('admin.people.admin.index', compact('users'));
    }

    public function create(){
        $roles    = Role::all(); 
        return view('admin.people.admin.create', compact('roles'));
    }

    public function invoice(){
        $users   = Admin::select('adminId', 'user_id', 'foto', 'name', 'telepon') 
                        ->with([
                            'user' => function ($q) {
                                $q->select('id', 'email', 'branch_id') 
                                ->with('branch:branchId,address');   
                            }
                        ])
                        ->get();
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
            'role'     => 'required|in:admin',
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

                $fotoPath = $request->hasFile('foto')
                    ? $request->file('foto')->store('admin_foto', 'public')
                    : null;

                $admin = Admin::create([
                    'user_id' => $user->id,
                    'name'    => $request->input('name'),
                    'telepon' => $request->input('telepon'),
                    'foto'    => $fotoPath,
                ]);

                $admin->logActivity(
                    ActivityLog::ACTION_CREATE,
                    "Admin {$admin->name} berhasil ditambahkan dengan email {$user->email}"
                );

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
            'name'      => 'nullable|string|max:75',
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

                $admin->logActivity(
                    ActivityLog::ACTION_UPDATE,
                    "Admin {$admin->name} berhasil diperbarui"
                );

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

                $admin->logActivity(
                    ActivityLog::ACTION_DELETE,
                    "Admin {$admin->name} dengan email {$user->email} telah dihapus"
                );

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
