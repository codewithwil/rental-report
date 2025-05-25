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
    Support\Facades\Validator,
    Support\Str
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
        // dd($req->all());
        return $this->executeTransaction(function () use ($req) {

            $validator = Validator::make($req->all(), [
                'branch_id'            => 'required|exists:branches,branchId',
                'category_id'          => 'required|exists:categories,categoryId',
                'brand_id'             => 'required|exists:brands,brandId',
                'photo'                => 'required|string',
                'name'                 => 'required|string|max:50',
                'plate_number'         => 'required|string|max:20',
                'color'                => 'required|string|max:20',
                'year'                 => 'required|date_format:Y', 
                'last_inspection_date' => 'required|date',
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

            $saveBase64File = function ($base64Data, $folder, $allowedTypes) {
                if (preg_match('/^data:([\w\/]+);base64,/', $base64Data, $matches)) {
                    $mimeType = $matches[1];
                    $extension = explode('/', $mimeType)[1];

                    if (!in_array($extension, $allowedTypes)) {
                        throw new \Exception("File harus berupa: " . implode(', ', $allowedTypes));
                    }

                    $fileData = substr($base64Data, strpos($base64Data, ',') + 1);
                    $fileData = base64_decode($fileData);

                    if ($fileData === false) {
                        throw new \Exception('File tidak valid.');
                    }

                    $fileName = $folder . '/' . Str::random(10) . '.' . $extension;
                    Storage::disk('public')->put($fileName, $fileData);
                    return $fileName;
                } else {
                    throw new \Exception('Format file tidak valid.');
                }
            };
            
            try {
                $photoData = json_decode($req->photo, true);
                $photoPath = $saveBase64File('data:' . $photoData['type'] . ';base64,' . $photoData['data'], 'vehicles', ['jpg','jpeg','png','gif']);

                $kirData = json_decode($req->kir_document, true);
                $kirDocPath = $saveBase64File('data:' . $kirData['type'] . ';base64,' . $kirData['data'], 'documents/vehicles/kir', ['pdf','jpg','jpeg','png']);

                $bpkbData = json_decode($req->bpkb_document, true);
                $bpkbDocPath = $saveBase64File('data:' . $bpkbData['type'] . ';base64,' . $bpkbData['data'], 'documents/vehicles/bpkb', ['pdf','jpg','jpeg','png']);

                $stnkData = json_decode($req->stnk_document, true);
                $stnkDocPath = $saveBase64File('data:' . $stnkData['type'] . ';base64,' . $stnkData['data'], 'documents/vehicles/stnk', ['pdf','jpg','jpeg','png']);

            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
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
                'stnk_date'            => $req->stnk_date,
                'bpkb_date'            => $req->bpkb_date,
                'kir_document'         => $kirDocPath,
                'bpkb_document'        => $bpkbDocPath,
                'stnk_document'        => $stnkDocPath,
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
                'photo'                => 'nullable|string', 
                'name'                 => 'nullable|string|max:50',
                'plate_number'         => 'nullable|string|max:20',
                'color'                => 'nullable|string|max:20',
                'year'                 => 'nullable|date_format:Y',
                'last_inspection_date' => 'nullable|date',
                'kir_expiry_date'      => 'nullable|date',
                'stnk_date'            => 'nullable|date',
                'bpkb_date'            => 'nullable|date',
                'kir_document'         => 'nullable|string',
                'bpkb_document'        => 'nullable|string',
                'stnk_document'        => 'nullable|string',
                'note'                 => 'nullable|string',
                'status'               => 'nullable|in:0,1,2,3',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $vehicle = Vehicle::findOrFail($vehicleId);

            $saveBase64File = function ($base64Data, $folder, $allowedTypes, $oldPath = null) {
                if (preg_match('/^data:([\w\/]+);base64,/', $base64Data, $matches)) {
                    $mimeType = $matches[1];
                    $extension = explode('/', $mimeType)[1];

                    if (!in_array($extension, $allowedTypes)) {
                        throw new \Exception("File harus berupa: " . implode(', ', $allowedTypes));
                    }

                    $fileData = substr($base64Data, strpos($base64Data, ',') + 1);
                    $fileData = base64_decode($fileData);

                    if ($fileData === false) {
                        throw new \Exception('File tidak valid.');
                    }

                    if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                        Storage::disk('public')->delete($oldPath);
                    }

                    $fileName = $folder . '/' . Str::random(10) . '.' . $extension;
                    Storage::disk('public')->put($fileName, $fileData);
                    return $fileName;
                } else {
                    throw new \Exception('Format file tidak valid.');
                }
            };

            try {
                if ($req->hasFile('photo')) {
                    if ($vehicle->photo && Storage::disk('public')->exists($vehicle->photo)) {
                        Storage::disk('public')->delete($vehicle->photo);
                    }
                    $path = $req->file('photo')->store('vehicles', 'public');
                    $vehicle->photo = $path;
                }
                elseif ($req->filled('photo')) {
                    $photoData = json_decode($req->photo, true);
                    if ($photoData && isset($photoData['data'], $photoData['type'], $photoData['name'])) {
                        $vehicle->photo = $saveBase64File(
                            'data:' . $photoData['type'] . ';base64,' . $photoData['data'],
                            'vehicles',
                            ['jpg','jpeg','png','gif'],
                            $vehicle->photo
                        );
                    } else {
                        throw new \Exception('Format foto tidak valid.');
                    }
                }

                if ($req->filled('kir_document')) {
                    $kirData = json_decode($req->kir_document, true);
                    $vehicle->kir_document = $saveBase64File(
                        'data:' . $kirData['type'] . ';base64,' . $kirData['data'],
                        'documents/vehicles/kir',
                        ['pdf','jpg','jpeg','png'],
                        $vehicle->kir_document
                    );
                }

                if ($req->filled('bpkb_document')) {
                    $bpkbData = json_decode($req->bpkb_document, true);
                    $vehicle->bpkb_document = $saveBase64File(
                        'data:' . $bpkbData['type'] . ';base64,' . $bpkbData['data'],
                        'documents/vehicles/bpkb',
                        ['pdf','jpg','jpeg','png'],
                        $vehicle->bpkb_document
                    );
                }

                if ($req->filled('stnk_document')) {
                    $stnkData = json_decode($req->stnk_document, true);
                    $vehicle->stnk_document = $saveBase64File(
                        'data:' . $stnkData['type'] . ';base64,' . $stnkData['data'],
                        'documents/vehicles/stnk',
                        ['pdf','jpg','jpeg','png'],
                        $vehicle->stnk_document
                    );
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }

            $fields = [
                'branch_id', 'category_id', 'brand_id',
                'name', 'plate_number', 'color', 'year',
                'last_inspection_date', 'kir_expiry_date',
                'stnk_date', 'bpkb_date','note', 'status',
            ];

            foreach ($fields as $field) {
                if ($req->filled($field)) {
                    $vehicle->{$field} = $req->{$field};
                }
            }

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
