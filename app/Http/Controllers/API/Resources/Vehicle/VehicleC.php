<?php

namespace App\Http\Controllers\API\Resources\Vehicle;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Company\Company,
    Models\Resources\Vehicle\Vehicle,
    Traits\DbBeginTransac,
    Models\Resources\Branch\Branch,
    Models\Resources\Brand\Brand,
    Models\Resources\Category\Category,
    Models\User,
    Models\Resources\Vehicle\VehicleDocument,
    Traits\AsignFile
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator,
};
use Illuminate\Support\Facades\Auth;

class VehicleC extends Controller
{
    use DbBeginTransac, AsignFile;
    public function index()
    {
        $vehicle = Vehicle::with(['user.branch'])
            ->where('status', '!=', Vehicle::STATUS_DELETED)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.resources.vehicle.index', compact('vehicle'));
    }


    public function invoice(){
        $vehicle = Vehicle::orderBy('created_at', 'asc')->get();
        $company = Company::first();
        return view('admin.resources.vehicle.invoice', compact('vehicle', 'company'));
    }

    public function create(){
        $branch   = Branch::all();
        $category = Category::all();
        $brand    = Brand::all();
        $users    = User::with('employee')
        ->whereHas('employee')
        ->get();
        return view('admin.resources.vehicle.create', compact('branch', 'category', 'brand', 'users'));
    }

    public function show($vehicleId){
        $vehicle = Vehicle::with('vehicleDocument')->findOrFail($vehicleId);
        return view('admin.resources.vehicle.details', compact('vehicle'));
    }

    public function edit($vehicleId){
        $vehicle  = Vehicle::with('vehicleDocument')->findOrFail($vehicleId);
        $branch   = Branch::all();
        $category = Category::all();
        $brand    = Brand::all();
        $users    = User::with('employee')
        ->whereHas('employee')
        ->get();
        return view('admin.resources.vehicle.update', compact('vehicle', 'branch', 'category', 'brand', 'users'));
    }

    public function store(Request $req)
    {
        return $this->executeTransaction(function () use ($req) {
            $validator = Validator::make($req->all(), [
                'branch_id'            => 'required|exists:branches,branchId',
                'category_id'          => 'required|exists:categories,categoryId',
                'brand_id'             => 'required|exists:brands,brandId',
                'user_id'              => 'required|exists:users,id',
                'photo'                => 'required|string',
                'name'                 => 'required|string|max:50',
                'plate_number'         => 'required|string|max:20',
                'color'                => 'required|string|max:20',
                'year'                 => 'required|digits:4|integer|between:1901,2155',
                'kir_expiry_date'      => 'required|date',
                'stnk_date'            => 'required|date',
                'bpkb_date'            => 'required|date',
                'kir_document'         => 'required|string',
                'bpkb_document'        => 'required|string',
                'stnk_document'        => 'required|string',
                'note'                 => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $validator->errors()->first());
            }

            try {
                $vehicle = new Vehicle();
                $vehicleFields = ['branch_id', 'user_id', 'category_id', 'brand_id', 'name', 'plate_number', 'color', 'year', 'note'];

                $this->assignFields($vehicle, $req, $vehicleFields);

                $vehicle->photo = $this->saveBase64File(
                    $req->photo,
                    'vehicles',
                    ['jpg', 'jpeg', 'png', 'gif']
                );

                $vehicle->save();

                $vehicleDoc = new VehicleDocument([
                    'vehicle_id'      => $vehicle->vehicleId,
                    'kir_expiry_date' => $req->kir_expiry_date,
                    'stnk_date'       => $req->stnk_date,
                    'bpkb_date'       => $req->bpkb_date,
                    'kir_document'    => $this->saveBase64File($req->kir_document, 'documents/vehicles/kir', ['pdf', 'jpg', 'jpeg', 'png']),
                    'bpkb_document'   => $this->saveBase64File($req->bpkb_document, 'documents/vehicles/bpkb', ['pdf', 'jpg', 'jpeg', 'png']),
                    'stnk_document'   => $this->saveBase64File($req->stnk_document, 'documents/vehicles/stnk', ['pdf', 'jpg', 'jpeg', 'png']),
                ]);
                $vehicleDoc->save();

                return redirect('/setting/vehicle')->with('success', 'Data Kendaraan berhasil ditambahkan!');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        });
    }

    public function update(Request $req, $vehicleId)
    {
        return $this->executeTransaction(function () use ($req, $vehicleId) {
            $vehicle = Vehicle::findOrFail($vehicleId);
            $vehicleDoc = $vehicle->vehicleDocument;

            $validator = Validator::make($req->all(), [
                'branch_id'      => 'nullable|exists:branches,branchId',
                'category_id'    => 'nullable|exists:categories,categoryId',
                'brand_id'       => 'nullable|exists:brands,brandId',
                'photo'          => 'nullable|string', 
                'name'           => 'nullable|string|max:50',
                'plate_number'   => 'nullable|string|max:20',
                'color'          => 'nullable|string|max:20',
                'year'           => 'nullable|date_format:Y',
                'note'           => 'nullable|string',
                'status'         => 'nullable|in:0,1,2,3',
                // vehicle_document fields
                'kir_expiry_date' => 'nullable|date',
                'stnk_date'       => 'nullable|date',
                'bpkb_date'       => 'nullable|date',
                'kir_document'    => 'nullable|string',
                'bpkb_document'   => 'nullable|string',
                'stnk_document'   => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            try {
                if ($req->filled('photo')) {
                    $vehicle->photo = $this->saveBase64File(
                        $req->photo, 
                        'vehicles', 
                        ['jpg', 'jpeg', 'png', 'gif'], 
                        $vehicle->photo
                    );
                }

                $vehicleFields = ['branch_id', 'category_id', 'brand_id', 'name', 'plate_number', 'color', 'year', 'note', 'status'];
                $this->assignFields($vehicle, $req, $vehicleFields);
                $vehicle->save();

                if ($vehicleDoc) {
                    if ($req->filled('kir_document')) {
                        $vehicleDoc->kir_document = $this->saveBase64File(
                            $req->kir_document, 
                            'documents/vehicles/kir', 
                            ['pdf', 'jpg', 'jpeg', 'png'], 
                            $vehicleDoc->kir_document
                        );
                    }

                    if ($req->filled('bpkb_document')) {
                        $vehicleDoc->bpkb_document = $this->saveBase64File(
                            $req->bpkb_document, 
                            'documents/vehicles/bpkb', 
                            ['pdf', 'jpg', 'jpeg', 'png'], 
                            $vehicleDoc->bpkb_document
                        );
                    }

                    if ($req->filled('stnk_document')) {
                        $vehicleDoc->stnk_document = $this->saveBase64File(
                            $req->stnk_document, 
                            'documents/vehicles/stnk', 
                            ['pdf', 'jpg', 'jpeg', 'png'], 
                            $vehicleDoc->stnk_document
                        );
                    }

                    $docFields = ['kir_expiry_date', 'stnk_date', 'bpkb_date'];
                    $this->assignFields($vehicleDoc, $req, $docFields);
                    $vehicleDoc->save();
                }

                return redirect('/setting/vehicle/')->with('success', 'Data Kendaraan berhasil diperbarui.');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
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
