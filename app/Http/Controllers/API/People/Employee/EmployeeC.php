<?php

namespace App\Http\Controllers\API\People\Employee;

use App\{
    Http\Controllers\Controller,
    Models\People\Employee\Employee,
    Models\Resources\Branch\Branch,
    Models\Resources\Company\Company,
    Models\User,
    Traits\DbBeginTransac,
    Models\History\ActivityLog\ActivityLog,
};

use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Hash,
    Support\Facades\Storage,
    Support\Facades\Validator
};

use Spatie\Permission\Models\Role;

class EmployeeC extends Controller
{
    use DbBeginTransac;

    public function index()
    {
        $users = Employee::select('employeeId', 'user_id', 'foto', 'name', 'telepon', 'address', 'gender')
                            ->with([
                                'user' => function ($query) {
                                    $query->select('id', 'email', 'branch_id') 
                                        ->with('branch:branchId,address');  
                                }
                            ])
                            ->get();

        return view('admin.people.employee.index', compact('users'));
    }

    
    public function create(){
        $roles    = Role::all(); 
        $branch   = Branch::all(); 
        return view('admin.people.employee.create', compact('roles', 'branch'));
    }

    public function invoice(){
        $users   = Employee::select('employeeId', 'user_id', 'foto', 'name', 'telepon', 'address', 'gender')
                            ->with([
                                'user' => function ($query) {
                                    $query->select('id', 'email', 'branch_id') 
                                        ->with('branch:branchId,address');  
                                }
                            ])
                            ->get();
        $company = Company::first();
        return view('admin.people.employee.invoice', compact('users', 'company'));
    }

    public function edit($employeeId)
    {
        $users = Employee::with('user')->findOrFail($employeeId);
        $roles = Role::all();
        $branch   = Branch::all(); 
        $userRole = $users->user->getRoleNames()->first(); 
    
        return view('admin.people.employee.update', compact('users', 'roles', 'userRole', 'branch'));
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'name'      => 'required|string|max:75',
            'telepon'   => 'required|digits_between:8,15', 
            'role'      => 'required|in:employee,petugas,owner,pengguna',
            'foto'      => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'address'   => 'required|string|max:65535',
            'birthdate' => 'required|date',
            'hire_date' => 'required|date',
            'salary'    => 'nullable|numeric|min:0',
            'gender'    => 'required|in:0,1',
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
                    ? $request->file('foto')->store('employee_foto', 'public')
                    : null;

                $employee = Employee::create([
                    'user_id'   => $user->id,
                    'name'      => $request->input('name'),
                    'telepon'   => $request->input('telepon'),
                    'foto'      => $fotoPath,
                    'address'   => $request->input('address'),
                    'birthdate' => $request->input('birthdate'),
                    'hire_date' => $request->input('hire_date'),
                    'salary'    => $request->input('salary'),
                    'gender'    => $request->input('gender'),
                ]);
                
                $employee->logActivity(
                    ActivityLog::ACTION_CREATE,
                    "Petugas {$employee->name} berhasil ditambahkan dengan email {$user->email}"
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Data employee berhasil ditambahkan.',
                ]);
            });
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $employeeId)
    {
        $request->validate([
            'branch_id' => 'nullable',
            'email'     => 'nullable|email',
            'password'  => 'nullable|min:6',
            'name'      => 'nullable|string|max:75',
            'telepon'   => 'nullable|digits_between:8,15', 
            'role'      => 'nullable|in:employee,petugas,owner,pengguna',
            'foto'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'address'   => 'nullable|string|max:65535',
            'birthdate' => 'nullable|date',
            'hire_date' => 'nullable|date',
            'salary'    => 'nullable|numeric|min:0',
            'gender'    => 'nullable|in:0,1',
        ]);

        try {
            return $this->executeTransaction(function () use ($request, $employeeId) {
                $employee = Employee::findOrFail($employeeId);
                $user = $employee->user;
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
                    if ($employee->foto && Storage::exists('public/' . $employee->foto)) {
                        Storage::delete('public/' . $employee->foto);
                    }
                    $employee->foto = $request->file('foto')->store('employee_foto', 'public');
                }

                $employee->fill($request->only([
                    'name', 'telepon', 'address', 'birthdate', 'hire_date', 'salary', 'gender'
                ]));

                $employee->save();
                
                $employee->logActivity(
                    ActivityLog::ACTION_UPDATE,
                    "Petugas {$employee->name} berhasil diperbarui"
                );
                return redirect('/people/employee')->with('success', 'Data user berhasil diperbarui.');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    
    
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($id);
            $user = $employee->user;
            $user->roles()->detach();
            if ($employee->foto && Storage::exists('public/' . $employee->foto)) {
                Storage::delete('public/' . $employee->foto);
            }
            
            $employee->logActivity(
                    ActivityLog::ACTION_DELETE,
                    "Petugas {$employee->name} dengan email {$user->email} telah dihapus"
            );
            $employee->delete();
            $user->delete();
    
            DB::commit();
            
            $message = 'Data user berhasil dihapus beserta peran-perannya';
            return redirect('/people/employee')->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            return redirect('/people/employee')->with('error', 'Employee tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
