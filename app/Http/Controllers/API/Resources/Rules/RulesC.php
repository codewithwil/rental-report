<?php

namespace App\Http\Controllers\API\Resources\Rules;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Company\Company,
    Models\Resources\Rules\Rules,
    Traits\DbBeginTransac,
    Models\History\ActivityLog\ActivityLog,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator,
};

class RulesC extends Controller
{
    use DbBeginTransac;
    public function index(){
        $rules = Rules::select('rulesId', 'content')->orderBy('created_at', 'asc')->get();
        return view('admin.resources.rules.index', compact('rules'));
    }

    public function invoice(){
        $rules = Rules::select('rulesId', 'content')->orderBy('created_at', 'asc')->get();
        $company  = Company::first();
        return view('admin.resources.rules.invoice', compact('rules', 'company'));
    }

    public function create(){
        return view('admin.resources.rules.create');
    }

    public function edit($rulesId){
        $rules = Rules::findOrFail($rulesId);
        return view('admin.resources.rules.update', compact('rules'));
    }

    public function store(Request $req)
    {
        return $this->executeTransaction(function () use ($req) {
            $validator = Validator::make($req->all(), [
                'content' => 'required|string|min:10',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $validator->getMessage());
            }

            $rules = Rules::create([
                'content' => $req->input('content'),
            ]);

            $rules->logActivity(
                ActivityLog::ACTION_CREATE,
                "Peraturan Perusahaan {$rules->name} berhasil ditambahkan"
            );

            return redirect('/setting/rules')
                ->with('success', 'Data Aturan Perusahaan berhasil ditambahkan!');
        });
    }
    
    public function update(Request $req, $rulesId)
    {
        $req->validate([
            'content' => 'nullable|string|min:10',
        ]);

        return $this->executeTransaction(function () use ($req, $rulesId) {
            $rules = Rules::findOrFail($rulesId);
            $rules->content = $req->content;
            $rules->save();

            $rules->logActivity(
                ActivityLog::ACTION_UPDATE,
                "Peraturan Perusahaan berhasil diperbarui"
            );

            return redirect('/setting/rules/')
                ->with('success', 'Data Aturan Perusahaan berhasil diperbarui.');
        });
    }
}
