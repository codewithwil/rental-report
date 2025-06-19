<?php

namespace App\Http\Controllers\API\Report\VehicleRepair;

use App\{
    Http\Controllers\Controller,
    Models\History\ActivityLog\ActivityLog,
    Models\Report\VehicleRepair\VehicleRepair,
    Models\Resources\Company\Company,
    Traits\DbBeginTransac
};
use App\Models\Notification\Notification;
use App\Models\Resources\Vehicle\Vehicle;
use App\Models\User;
use App\Traits\HasUploadFile;
use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VehicleRepairC extends Controller
{
      use DbBeginTransac;

    public function index()
    {
        $vehicleRepair = VehicleRepair::get();

        return view('admin.report.vehicleRepair.index', compact('vehicleRepair'));
    }

    public function invoice(){
       $vehicleRepair = VehicleRepair::with(['vehicle.branch', 'user', 'photo'])->findOrFail($id);
        $company = Company::first();
        return view('admin.report.vehicleRepair.invoice', compact('vehicleRepair', 'company'));
    }

    public function detail($vehicleRepId){
       $vehicleRepair = VehicleRepair::with(['vehicle.branch', 'user', 'photo'])->findOrFail($vehicleRepId);
        $company = Company::first();
        return view('admin.report.vehicleRepair.details', compact('vehicleRepair', 'company'));
    }

    public function create(){
        $vehicle = Vehicle::where('status', '!=', Vehicle::STATUS_DELETED)->get();
        return view('admin.report.vehicleRepair.create', compact('vehicle'));
    }

    public function edit($vehicleRepId){
        $vehicle = Vehicle::where('status', '!=', Vehicle::STATUS_DELETED)->get();
        $vehicleRepair = VehicleRepair::with('photo')->findOrFail($vehicleRepId);
        return view('admin.report.vehicleRepair.update', compact('vehicleRepair', 'vehicle'));
    }

    public function approve($vehicleRepId)
    {
        return $this->executeTransaction(function () use ($vehicleRepId) {
            $vehicleRepair = VehicleRepair::with('vehicle')->findOrFail($vehicleRepId);

            $vehicleRepair->update([
                'statusRepair' => VehicleRepair::STATUSREP_COMPLETED
            ]);

            $vehicleRepair->logActivity(
                ActivityLog::ACTION_UPDATE,
                "Pengajuan Perbaikan Kendaraan {$vehicleRepair->vehicle->name} telah disetujui."
            );

                return redirect('/report/vehicleRepair/')
                ->with('success', 'Data Pengajuan Perbaikan Disetujui!');
        });
    }

    public function reject(Request $req, $vehicleRepId)
    {
        return $this->executeTransaction(function () use ($req, $vehicleRepId) {
            try {
                $req->validate([
                    'note' => 'required|string|max:500'
                ]);

                $vehicleRepair = VehicleRepair::with('vehicle')->findOrFail($vehicleRepId);
                $vehicleRepair->statusRepair = VehicleRepair::STATUSREP_REJECTED;
                $vehicleRepair->save();

                Notification::create([
                    'user_id' => $vehicleRepair->user_id,
                    'title'   => 'Pengajuan Perbaikan Kendaraan Ditolak',
                    'message' => $req->note,
                    'link'    => url('/report/vehicleRepair/detail/' . $vehicleRepair->vehicleRepId),
                    'is_read' => false,
                ]);

                $vehicleRepair->logActivity(
                    ActivityLog::ACTION_UPDATE,
                    "Pengajuan Perbaikan Kendaraan {$vehicleRepair->vehicle->name} telah ditolak."
                );

                return redirect('/report/vehicleRepair/')
                    ->with('success', 'Data Pengajuan Perbaikan Kendaraan Ditolak');
            } catch (\Exception $e) {
                return redirect('/report/vehicleRepair/')
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        });
    }

    public function store(Request $req)
    {
        return $this->executeTransaction(function () use ($req) {

            $validator = Validator::make($req->all(), [
                'vehicle_id'      => 'required|exists:vehicles,vehicleId',
                'submission_date' => 'required|date',
                'description'     => 'nullable|string',
                'estimated_cost'  => 'nullable|numeric|min:0',
                'photos'          => 'nullable|array',
                'photos.*.name'   => 'required|string',
                'photos.*.type'   => 'required|string',
                'photos.*.size'   => 'required|numeric|max:10485760',
                'photos.*.base64' => 'required|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $vehicleRepair = VehicleRepair::create([
                'vehicle_id'      => $req->vehicle_id,
                'user_id'         => Auth::user()->id,
                'submission_date' => $req->submission_date,
                'description'     => $req->description,
                'estimated_cost'  => $req->estimated_cost ?? 0,
                'status'          => VehicleRepair::STATUS_ACTIVE,
            ]);

            if ($req->has('photos')) {
                $vehicleRepair->uploadBase64Files($req->photos, 'photo', 'vehicle_repair');
            }

            $vehicleRepair->load('vehicle');
            $vehicleRepair->logActivity(
                ActivityLog::ACTION_CREATE,
                "Pengajuan Perbaikan Kendaraan {$vehicleRepair->vehicle->name} berhasil ditambahkan"
            );
            return redirect('/report/vehicleRepair/')
                ->with('success', 'Data Pengajuan Perbaikan Kendaraan berhasil ditambahkan!');
        });
    }

    public function update(Request $req, $vehicleRepId)
    {
        Log::info('Mulai proses update vehicle repair', [
            'vehicleRepId' => $vehicleRepId,
            'request' => $req->all(),
        ]);

        $decodedPhotos = collect($req->input('photos', []))
            ->map(function ($photo) {
                if (is_string($photo)) {
                    $decoded = json_decode($photo, true);
                    $photo = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
                }
                if (isset($photo['data'])) {
                    $photo['base64'] = $photo['data'];
                    unset($photo['data']);
                }
                return $photo;
            })->filter()->values()->all();

        $req->merge(['photos' => $decodedPhotos]);

        return $this->executeTransaction(function () use ($req, $vehicleRepId, $decodedPhotos) {

            $vehicleRepair = VehicleRepair::with('photo')->findOrFail($vehicleRepId);

            $validator = Validator::make(array_merge($req->all(), ['photos' => $decodedPhotos]), [
                'vehicle_id'      => 'required|exists:vehicles,vehicleId',
                'user_id'         => 'sometimes|exists:users,id',
                'submission_date' => 'required|date',
                'description'     => 'nullable|string',
                'estimated_cost'  => 'nullable|numeric|min:0',
                'photos'          => 'nullable|array',
                'photos.*.name'   => 'required|string',
                'photos.*.type'   => 'required|string',
                'photos.*.size'   => 'required|numeric|max:10485760',
                'photos.*.base64' => 'required|string',
                'keep_photos'     => 'nullable|array',
            ]);

            if ($validator->fails()) {
                Log::error('Validasi gagal', ['errors' => $validator->errors()]);
                return back()->withErrors($validator)->withInput();
            }

            $vehicleRepair->update([
                'vehicle_id'      => $req->vehicle_id,
                'user_id'         => $req->user_id ?? Auth::id(),
                'submission_date' => $req->submission_date,
                'description'     => $req->description,
                'estimated_cost'  => $req->estimated_cost ?? $vehicleRepair->estimated_cost,
            ]);

            $keepIds = $req->input('keep_photos', []);
            $photosToDelete = $vehicleRepair->photo->filter(
                fn($photo) => !in_array($photo->filesId, $keepIds)
            );

            foreach ($photosToDelete as $photo) {
                try {
                    Storage::disk('public')->delete($photo->path);
                    $photo->delete();
                    Log::info('Foto dihapus', ['photo_id' => $photo->id]);
                } catch (\Throwable $e) {
                    Log::error('Gagal menghapus foto', [
                        'photo_id' => $photo->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            if (!empty($decodedPhotos)) {
                try {
                    $vehicleRepair->uploadBase64Files($decodedPhotos, 'photo', 'vehicle_repair');
                    Log::info('Upload foto baru selesai', ['jumlah' => count($decodedPhotos)]);
                } catch (\Throwable $e) {
                    Log::error('Gagal upload foto baru', ['error' => $e->getMessage()]);
                }
            }

            $vehicleRepair->load('vehicle')->logActivity(
                ActivityLog::ACTION_UPDATE,
                "Pengajuan Perbaikan Kendaraan {$vehicleRepair->vehicle->name} berhasil diperbarui"
            );

            Log::info('Update pengajuan berhasil', ['vehicleRepId' => $vehicleRepId]);

            return redirect('/report/vehicleRepair/')
                ->with('success', 'Data Pengajuan Perbaikan Kendaraan berhasil diperbarui.');
        });
    }

    public function delete($vehicleRepId)
    {
        return $this->executeTransaction(function () use ($vehicleRepId) {
            $vehicleRepair = VehicleRepair::findOrFail($vehicleRepId);
            $vehicleRepair->status = VehicleRepair::STATUS_INACTIVE;
            $vehicleRepair->save();

            $vehicleRepair->load('vehicle');
            $vehicleRepair->logActivity(
                ActivityLog::ACTION_DELETE,
                "Pengajuan Perbaikan Kendaraan {$vehicleRepair->vehicle->name} telah dihapus"
            );

            return redirect('/report/vehicleRepair/')
                ->with('success', 'Data Pengajuan Perbaikan Kendaraan berhasil dihapus.');
        });
    }
}
