<?php

namespace App\Http\Controllers\API\Resources\Vehicle;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Company\Company,
    Models\Resources\Vehicle\Vehicle,
    Traits\DbBeginTransac,
    Models\Resources\Branch\Branch,
    Models\Resources\Brand\Brand,
    Models\Resources\Category\Category
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};
use Illuminate\Support\Facades\Storage;

class VehicleC extends Controller
{
     use DbBeginTransac;
    public function index(){
       $vehicle = Vehicle::where('status', '!=', Vehicle::STATUS_DELETED)
            ->orderBy('created_at', 'asc')
            ->get();
        return view('admin.resources.vehicle.index', compact('vehicle'));
    }

    public function invoice(){
        $vehicle = Vehicle::orderBy('created_at', 'asc')->get();
        $company  = Company::first();
        return view('admin.resources.vehicle.invoice', compact('vehicle', 'company'));
    }

    public function create(){
        $branch   = Branch::all();
        $category = Category::all();
        $brand    = Brand::all();
        return view('admin.resources.vehicle.create', compact('branch', 'category', 'brand'));
    }

    public function show($vehicleId){
        $vehicle = Vehicle::findOrFail($vehicleId);
        return view('admin.resources.vehicle.details', compact('vehicle'));
    }

    public function edit($vehicleId){
        $vehicle = Vehicle::findOrFail($vehicleId);
        $branch   = Branch::all();
        $category = Category::all();
        $brand    = Brand::all();
        return view('admin.resources.vehicle.update', compact('vehicle', 'branch', 'category', 'brand'));
    }

    public function store(Request $req)
    {
        return $this->executeTransaction(function () use ($req) {
            $validator = Validator::make($req->all(), [
                'branch_id'            => 'required|exists:branches,branchId',
                'category_id'          => 'required|exists:categories,categoryId',
                'brand_id'             => 'required|exists:brands,brandId',
                'photo'                => 'required|image|mimes:jpg,jpeg,png|max:2048',
                'name'                 => 'required|string|max:50',
                'plate_number'         => 'required|string|max:20',
                'color'                => 'required|string|max:20',
                'year'                 => 'required|date_format:Y', 
                'last_inspection_date' => 'required|date',
                'kir_expiry_date'      => 'required|date',
                'tax_date'             => 'required|date',
                'note'                 => 'required|string',
            ]);


            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $validator->errors()->first());
            }


            $photoPath = null;
            if ($req->hasFile('photo')) {
                $photoPath = $req->file('photo')->store('vehicles', 'public');
            }

            Vehicle::create([
                'branch_id'            => $req->branch_id,
                'category_id'          => $req->category_id,
                'brand_id'             => $req->brand_id,
                'photo'                => $photoPath,
                'name'                 => $req->name,
                'plate_number'         => $req->plate_number,
                'color'                => $req->color,
                'year'                 => $req->year,
                'last_inspection_date' => $req->last_inspection_date,
                'kir_expiry_date'      => $req->kir_expiry_date,
                'tax_date'             => $req->tax_date,
                'note'                 => $req->note,
            ]);


            return redirect('/setting/vehicle')
                ->with('success', 'Data Kendaraan berhasil ditambahkan!');
        });
    }
    
    public function update(Request $req, $vehicleId)
    {
        return $this->executeTransaction(function () use ($req, $vehicleId) {
            $validator = Validator::make($req->all(), [
                'branch_id'            => 'nullable|exists:branches,branchId',
                'category_id'          => 'nullable|exists:categories,categoryId',
                'brand_id'             => 'nullable|exists:brands,brandId',
                'photo'                => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'name'                 => 'nullable|string|max:50',
                'plate_number'         => 'nullable|string|max:20',
                'color'                => 'nullable|string|max:20',
                'year'                 => 'nullable|date_format:Y', 
                'last_inspection_date' => 'nullable|date',
                'kir_expiry_date'      => 'nullable|date',
                'tax_date'             => 'nullable|date',
                'note'                 => 'nullable|string',
                'status'               => 'nullable|in:0,1,2,3',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $vehicle = Vehicle::findOrFail($vehicleId);

            if ($req->hasFile('photo')) {
                if ($vehicle->photo && Storage::disk('public')->exists($vehicle->photo)) {
                    Storage::disk('public')->delete($vehicle->photo);
                }
                $vehicle->photo = $req->file('photo')->store('vehicles', 'public');
            }

            $vehicle->branch_id            = $req->branch_id;
            $vehicle->category_id          = $req->category_id;
            $vehicle->brand_id             = $req->brand_id;
            $vehicle->name                 = $req->name;
            $vehicle->plate_number         = $req->plate_number;
            $vehicle->color                = $req->color;
            $vehicle->year                 = $req->year;
            $vehicle->last_inspection_date = $req->last_inspection_date;
            $vehicle->kir_expiry_date      = $req->kir_expiry_date;
            $vehicle->tax_date             = $req->tax_date;
            $vehicle->note                 = $req->note;
            $vehicle->status               = $req->status;

            $vehicle->save();

            return redirect('/setting/vehicle/')
                ->with('success', 'Data Kendaraan berhasil diperbarui.');
        });
    }


    public function delete($vehicleId)
    {
        try {
            $vehicle = Vehicle::findOrFail($vehicleId);
            $vehicle->status = Vehicle::STATUS_DELETED;
            $vehicle->save();

            return redirect('/setting/vehicle/')
                ->with('success', 'Data Kendaraan Berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect('/setting/vehicle/')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
