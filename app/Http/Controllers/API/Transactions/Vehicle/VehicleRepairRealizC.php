<?php

namespace App\Http\Controllers\API\Transactions\Vehicle;

use App\{
    Http\Controllers\Controller,
    Models\History\ActivityLog\ActivityLog,
    Models\Report\VehicleRepair\VehicleRepair,
    Models\Resources\Company\Company,
    Models\Transactions\Vehicle\VehicleRepairRealiz,
    Traits\DbBeginTransac,
    Models\Transactions\Payment\PaymentAmount
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

class VehicleRepairRealizC extends Controller
{
    use DbBeginTransac;

    public function index()
    {
        $vehicleRepair = VehicleRepairRealiz::get();
        return view('admin.transactions.vehicleRepair.index', compact('vehicleRepair'));
    }

    public function invoice() 
    {
        $vehicleRepair = VehicleRepairRealiz::get();
        $company = Company::first();
        return view('admin.transactions.vehicleRepair.invoice', compact('vehicleRepair', 'company'));
    }

    public function create()
    {
        $vehicle = VehicleRepair::where('status', VehicleRepair::STATUS_ACTIVE)->get();
        return view('admin.transactions.vehicleRepair.create', compact('vehicle'));
    }

    public function edit($vehicleRepairRealId)
    {
        $vehicle = VehicleRepair::where('status', VehicleRepair::STATUS_ACTIVE)->get();
        $vehicleRepair = VehicleRepairRealiz::with('photo')->findOrFail($vehicleRepairRealId);
        return view('admin.transactions.vehicleRepair.update', compact('vehicleRepair', 'vehicle'));
    }

    public function store(Request $req)
    {
        return $this->executeTransaction(function () use ($req) {
            $validator = Validator::make($req->all(), [
                'vehicleRep_id' => 'required|exists:vehicle_repairs,vehicleRepId',
                'completeDate'  => 'required|date',
                'notes'         => 'nullable|string',
                'amount'        => 'required|numeric|min:0',
                'photo.name'    => 'nullable|string',
                'photo.type'    => 'nullable|string',
                'photo.size'    => 'nullable|numeric|max:10485760',
                'photo.base64'  => 'nullable|string',
            ]);


            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $vehicleRepair = VehicleRepairRealiz::create([
                'vehicleRep_id' => $req->vehicleRep_id,
                'completeDate'  => $req->completeDate,
                'notes'         => $req->notes,
                'status'        => VehicleRepairRealiz::STATUS_ACTIVE,
            ]);

            if ($req->filled('photo.base64')) {
                $vehicleRepair->uploadBase64File($req->photo, 'photo', 'vehicle_repair_transactions');
            }

            $vehicleRepair->paymentAmount()->create([
                'amount'        => $req->amount,
                'type'          => PaymentAmount::TYPE_KElUAR,
            ]);

            $vehicleRepair->load('vehicle')->logActivity(
                ActivityLog::ACTION_CREATE,
                "Transaksi Realisasi Perbaikan Kendaraan {$vehicleRepair->vehicle->name} berhasil ditambahkan"
            );

            return redirect('/transactions/vehicleRepairReal/')
                ->with('success', 'Data Transaksi Realisasi Perbaikan Kendaraan berhasil ditambahkan!');
        });
    }

    public function update(Request $req, $vehicleRepairRealId)
    {
        return $this->executeTransaction(function () use ($req, $vehicleRepairRealId) {
            $vehicleRepair = VehicleRepairRealiz::with('photo')->findOrFail($vehicleRepairRealId);

            $validator = Validator::make($req->all(), [
                'vehicleRep_id' => 'nullable|exists:vehicle_repairs,vehicleRepId',
                'completeDate'  => 'nullable|date',
                'notes'         => 'nullable|string',
                'amount'        => 'required|numeric|min:0',
                'photo.name'    => 'nullable|string',
                'photo.type'    => 'nullable|string',
                'photo.size'    => 'nullable|numeric|max:10485760',
                'photo.base64'  => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $vehicleRepair->update([
                'vehicleRep_id' => $req->vehicleRep_id ?? $vehicleRepair->vehicleRep_id,
                'completeDate'  => $req->completeDate ?? $vehicleRepair->completeDate,
                'notes'         => $req->notes,
            ]);

            
            if ($req->filled('photo.base64')) {
                $vehicleRepair->uploadBase64File($req->photo, 'photo', 'vehicle_repair_transactions');
            }

            $payment = $vehicleRepair->paymentAmount()->first();
            if ($payment) {
                $payment->update([
                    'amount' => $req->amount,
                ]);
            } else {
                $vehicleRepair->paymentAmount()->create([
                    'amount' => $req->amount,
                    'type'   => PaymentAmount::TYPE_KElUAR,
                    'status' => PaymentAmount::STATUS_ACTIVE,
                ]);
            }

            $vehicleRepair->load('vehicle')->logActivity(
                ActivityLog::ACTION_UPDATE,
                "Realisasi Perbaikan Kendaraan {$vehicleRepair->vehicle->name} berhasil diperbarui"
            );

            return redirect('/transactions/vehicleRepairReal/')
                ->with('success', 'Data Realisasi Perbaikan Kendaraan berhasil diperbarui.');
        });
    }

    public function delete($vehicleRepairRealId)
    {
        return $this->executeTransaction(function () use ($vehicleRepairRealId) {
            $vehicleRepair         = VehicleRepairRealiz::findOrFail($vehicleRepairRealId);
            $vehicleRepair->status = VehicleRepairRealiz::STATUS_INACTIVE;
            $vehicleRepair->save();

            $vehicleRepair->load('vehicle')->logActivity(
                ActivityLog::ACTION_DELETE,
                "Realisasi Perbaikan Kendaraan {$vehicleRepair->vehicle->name} telah dihapus"
            );

            return redirect('/transactions/vehicleRepairReal/')
                ->with('success', 'Data Realisasi Perbaikan Kendaraan berhasil dihapus.');
        });
    }
}
