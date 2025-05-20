<?php

namespace App\Http\Controllers\API\Resources\Rules;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Company\Company,
    Models\Resources\Rules\Rules,
    Traits\DbBeginTransac
};

use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Validator,
};

class RulesC extends Controller
{
    use DbBeginTransac;
    public function index(){
        $rules = Rules::orderBy('created_at', 'asc')->get();
        return view('admin.resources.rules.index', compact('rules'));
    }

    public function invoice(){
        $rules = Rules::orderBy('created_at', 'asc')->get();
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
                return redirect()->back()->withErrors($validator)->withInput();
            }

            Rules::create([
                'content' => $req->input('content'),
            ]);

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

            return redirect('/setting/rules/')
                ->with('success', 'Data Aturan Perusahaan berhasil diperbarui.');
        });
    }


    public function delete($rulesId)
    {
        try {
            $rules = Rules::findOrFail($rulesId);
            $rules->delete();

            return redirect('/setting/rules/')
                ->with('success', 'Data Aturan Perusahaan Berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect('/setting/rules/')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
