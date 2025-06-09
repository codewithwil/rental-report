<?php

namespace App\Http\Controllers\API\Resources\Brand;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Brand\Brand,
    Models\Resources\Company\Company,
    Traits\DbBeginTransac,
    Models\History\ActivityLog\ActivityLog
};

use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Validator
};

class BrandC extends Controller
{
    use DbBeginTransac;
      public function index(){
        $brand = Brand::where('status', Brand::STATUS_ACTIVE)
                            ->orderBy('created_at', 'asc')
                            ->get();
        return view('admin.resources.brand.index', compact('brand'));
    }

    public function invoice(){
        $brand = Brand::where('status', Brand::STATUS_ACTIVE)->orderBy('created_at', 'asc')->get();
        $company  = Company::first();
        return view('admin.resources.brand.invoice', compact('brand', 'company'));
    }

    public function create(){
        return view('admin.resources.brand.create');
    }

    public function edit($brandId){
        $brand = Brand::findOrFail($brandId);
        return view('admin.resources.brand.update', compact('brand'));
    }

    public function store(Request $req)
    {
        return $this->executeTransaction(function () use ($req) {
            $validator = Validator::make($req->all(), [
                'name' => 'required|string|min:3|max:50',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $brand = Brand::create([
                'name' => $req->input('name'),
            ]);

            $brand->logActivity(
                ActivityLog::ACTION_CREATE,
                "Merk {$brand->name} berhasil ditambahkan"
            );

            return response()->json([
                'success' => true,
                'message' => 'Data Merek Kendaraan berhasil ditambahkan!',
            ]);
        });
    }

    
    public function update(Request $req, $brandId)
    {
        $req->validate([
            'name' => 'nullable|string|min:3|max:50'
        ]);

        return $this->executeTransaction(function () use ($req, $brandId) {
            $brand = Brand::findOrFail($brandId);
            $brand->name = $req->name;
            $brand->save();
            $brand->logActivity(
                ActivityLog::ACTION_UPDATE,
                "Merk {$brand->name} berhasil diperbarui"
            );
            return redirect('/setting/brand/')
                ->with('success', 'Data Merek Kendaraan berhasil diperbarui.');
        });
    }


    public function delete($brandId)
    {
        try {
            $brand = Brand::findOrFail($brandId);
            $brand->status = Brand::STATUS_INACTIVE;
            $brand->save();
            $brand->logActivity(
               ActivityLog::ACTION_DELETE,
                "Merk {$brand->name} telah dihapus"
            );
            return redirect('/setting/brand/')
                ->with('success', 'Data Merek Kendaraan Berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect('/setting/brand/')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
