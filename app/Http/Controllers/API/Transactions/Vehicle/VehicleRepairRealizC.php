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
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

class VehicleRepairRealizC extends Controller
{
    use DbBeginTransac;

    public function index()
    {
        $vehicleRepairReal = VehicleRepairRealiz::with(['vehicleRepair','paymentAmount'])->get();
        return view('admin.transactions.vehicleRepair.index', compact('vehicleRepairReal'));
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

    public function show($vehicleRepairRealId){
          $vehicleRepair     = VehicleRepair::where('status', VehicleRepair::STATUS_ACTIVE)->get();
        $vehicleRepairReal = VehicleRepairRealiz::with(['vehicleRepair','paymentAmount', 'photo'])
                                                ->findOrFail($vehicleRepairRealId);
        return view('admin.transactions.vehicleRepair.details', compact('vehicleRepairReal', 'vehicleRepair'));
    }

    public function pdf($vehicleRepairRealId)
    {
        $vehicleRepairReal = VehicleRepairRealiz::with([
            'vehicleRepair.vehicle.brand',
            'vehicleRepair.vehicle.branch',
            'vehicleRepair.user.admin',
            'vehicleRepair.user.supervisor',
            'vehicleRepair.user.employee',
            'photo',
            'paymentAmount'
        ])->findOrFail($vehicleRepairRealId);

        $company = Company::first();
        $pdf     = Pdf::loadView('admin.transactions.vehicleRepair.pdf', compact('vehicleRepairReal', 'company'));
        return $pdf->stream('NotaPerbaikan_' . now()->format('Ymd_His') . '.pdf');
    }

    public function edit($vehicleRepairRealId)
    {
        $vehicleRepair     = VehicleRepair::where('status', VehicleRepair::STATUS_ACTIVE)->get();
        $vehicleRepairReal = VehicleRepairRealiz::with(['vehicleRepair','paymentAmount', 'photo'])
                                                ->findOrFail($vehicleRepairRealId);
        return view('admin.transactions.vehicleRepair.update', compact('vehicleRepairReal', 'vehicleRepair'));
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
                'type'          => PaymentAmount::TYPE_KELUAR,
                'status'        => PaymentAmount::STATUS_ACTIVE,
            ]);

            $vehicleRepair->logActivity(
                ActivityLog::ACTION_CREATE,
                "Nota Perbaikan Kendaraan {$vehicleRepair->completeDate} berhasil ditambahkan"
            );

            return redirect('/transactions/vehicleRepairReal/')
                ->with('success', 'Data Nota Perbaikan Kendaraan berhasil ditambahkan!');
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
                    'type'   => PaymentAmount::TYPE_KELUAR,
                    'status' => PaymentAmount::STATUS_ACTIVE,
                ]);
            }

            $vehicleRepair->logActivity(
                ActivityLog::ACTION_UPDATE,
                "Nota Perbaikan Kendaraan {$vehicleRepair->completeDate} berhasil diperbarui"
            );

            return redirect('/transactions/vehicleRepairReal/')
                ->with('success', 'Data Nota Perbaikan Kendaraan berhasil diperbarui.');
        });
    }

    public function delete($vehicleRepairRealId)
    {
        return $this->executeTransaction(function () use ($vehicleRepairRealId) {
            $vehicleRepair         = VehicleRepairRealiz::findOrFail($vehicleRepairRealId);
            $vehicleRepair->status = VehicleRepairRealiz::STATUS_INACTIVE;
            $vehicleRepair->save();

            $vehicleRepair->logActivity(
                ActivityLog::ACTION_DELETE,
                "Nota Perbaikan Kendaraan {$vehicleRepair->completeDate} telah dihapus"
            );

            return redirect('/transactions/vehicleRepairReal/')
                ->with('success', 'Data Nota Perbaikan Kendaraan berhasil dihapus.');
        });
    }
}
