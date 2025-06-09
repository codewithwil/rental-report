<?php

namespace App\Http\Controllers\API\People\Supervisor;

use App\{
    Http\Controllers\Controller,
    Models\People\Supervisor\Supervisor,
    Models\Resources\Branch\Branch,
    Models\Resources\Company\Company,
    Models\User,
    Traits\DbBeginTransac,
    Models\History\ActivityLog\ActivityLog
};

use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Hash,
    Support\Facades\Storage,
    Support\Facades\Validator
};

use Spatie\Permission\Models\Role;

class SupervisorC extends Controller
{
    use DbBeginTransac;

    public function index()
    {
        $users = Supervisor::with(['user.branch'])->get();
        return view('admin.people.supervisor.index', compact('users'));
    }
    
    public function create(){
        $roles    = Role::all(); 
        $branch    = Branch::all(); 
        return view('admin.people.supervisor.create', compact('roles', 'branch'));
    }

    public function invoice(){
        $users   = Supervisor::all();
        $company = Company::first();
        return view('admin.people.supervisor.invoice', compact('users', 'company'));
    }

    public function edit($supervisorId)
    {
        $users = Supervisor::with('user')->findOrFail($supervisorId);
        $roles = Role::all(); 
        $branch = Branch::all(); 
        $userRole = $users->user->getRoleNames()->first(); 
    
        return view('admin.people.supervisor.update', compact('users', 'roles', 'userRole', 'branch'));
    }   

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'branch_id' => 'required',
            'name'      => 'required|string|max:255',
            'telepon'   => 'required|numeric',
            'role'      => 'required|in:supervisor,petugas,owner,pengguna',
            'foto'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
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
                    'branch_id' => $request->input('branch_id'),
                ]);

                $user->assignRole($request->input('role'));

                $fotoPath = $request->hasFile('foto')
                    ? $request->file('foto')->store('supervissor_foto', 'public')
                    : null;

                $supervisor = Supervisor::create([
                    'user_id' => $user->id,
                    'name'    => $request->input('name'),
                    'telepon' => $request->input('telepon'),
                    'foto'    => $fotoPath,
                ]);

                $supervisor->logActivity(
                    ActivityLog::ACTION_CREATE,
                    "Supervisor {$supervisor->name} berhasil ditambahkan dengan email {$user->email}"
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Data supervisor berhasil ditambahkan.',
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $supervisorId)
    {
        $request->validate([
            'email'     => 'nullable|email',
            'name'      => 'nullable|string|max:255',
            'telepon'   => 'nullable|digits_between:10,15',
            'password'  => 'nullable|min:8',
            'role'      => 'nullable|exists:roles,name',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'branch_id' => 'nullable',
        ]);

        try {
            return $this->executeTransaction(function () use ($request, $supervisorId) {
                $supervisor = Supervisor::findOrFail($supervisorId);
                $user = $supervisor->user;

                if ($request->filled('email')) {
                    $user->email = $request->email;
                }

                if ($request->filled('password')) {
                    $user->password = Hash::make($request->password);
                }

                if ($request->filled('branch_id')) {
                    $user->branch_id = $request->branch_id;
                }

                $user->save();

                if ($request->filled('role')) {
                    $user->syncRoles($request->role);
                }

                if ($request->hasFile('foto')) {
                    if ($supervisor->foto && Storage::exists('public/' . $supervisor->foto)) {
                        Storage::delete('public/' . $supervisor->foto);
                    }

                    $fotoPath = $request->file('foto')->store('supervissor_foto', 'public');
                    $supervisor->foto = $fotoPath;
                }

                if ($request->filled('name')) {
                    $supervisor->name = $request->name;
                }

                if ($request->filled('telepon')) {
                    $supervisor->telepon = $request->telepon;
                }

                $supervisor->save();

                $supervisor->logActivity(
                    ActivityLog::ACTION_UPDATE,
                    "Supervisor {$supervisor->name} berhasil diperbarui"
                );

                return redirect('/people/supervisor')->with('success', 'Data user berhasil diperbarui.');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    } 
    
    public function delete($supervisorId)
    {
        DB::beginTransaction();
        try {
            $supervisor = Supervisor::findOrFail($supervisorId);
            $user = $supervisor->user;
            $user->roles()->detach();
            if ($supervisor->foto && Storage::exists('public/' . $supervisor->foto)) {
                Storage::delete('public/' . $supervisor->foto);
            }
            $supervisor->logActivity(
                    ActivityLog::ACTION_DELETE,
                    "Supervisor {$supervisor->name} dengan email {$user->email} telah dihapus"
            );
            $supervisor->delete();
            $user->delete();
    
            DB::commit();
            
            $message = 'Data user berhasil dihapus beserta peran-perannya';
            return redirect('/people/supervisor')->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect('/people/supervisor')->with('error', 'Supervisor tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
