<?php

namespace App\Http\Controllers\API\Transactions\RentCar;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Company\Company,
    Models\Resources\Vehicle\Vehicle,
    Models\Transactions\RentCar\RentCar,
    Traits\DbBeginTransac,
    Models\History\ActivityLog\ActivityLog,
    Models\Transactions\Payment\PaymentAmount
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Carbon\Carbon;

class RentCarC extends Controller
{
    use DbBeginTransac;

    public function index()
    {
        $rentCar = RentCar::with(['vehicle','paymentAmount'])->get();
        return view('admin.transactions.rentCar.index', compact('rentCar'));
    }

    public function invoice() 
    {
        $rentCar = RentCar::get();
        $company = Company::first();
        return view('admin.transactions.rentCar.invoice', compact('rentCar', 'company'));
    }

    public function create()
    {
         $rentedVehicleIds = RentCar::whereIn('status', [
            RentCar::STATUS_PENDING,
            RentCar::STATUS_ONRENT
        ])
        ->pluck('vehicle_id');
        $vehicle = Vehicle::where('status', Vehicle::STATUS_ACTIVE)
                ->whereNotIn('vehicleId', $rentedVehicleIds)
                ->get();
        return view('admin.transactions.rentCar.create', compact('vehicle'));
    }

    public function show($rentCarId){
        $rentCar = RentCar::with(['vehicle','paymentAmount', 'photo'])
                                                ->findOrFail($rentCarId);
        return view('admin.transactions.rentCar.details', compact('rentCar'));
    }

    public function edit($rentCarId)
    {
        $vehicle = Vehicle::where('status', Vehicle::STATUS_ACTIVE)->get();
        $rentCar = RentCar::with(['rentCar','paymentAmount', 'photo'])
                                                ->findOrFail($rentCarId);
        return view('admin.transactions.rentCar.update', compact('rentCar', 'vehicle'));
    }

    public function store(Request $req)
    {
        return $this->executeTransaction(function () use ($req) {
            $validator = Validator::make($req->all(), [
                'vehicle_id'     => 'required|exists:vehicles,vehicleId',
                'renter_name'    => 'required|string|min:3|max:75',
                'renter_address' => 'required|string|min:3',
                'renter_phone'   => 'required|numeric|min:0',
                'startDate'      => 'required|date',
                'endDate'        => 'required|date',
                'pricePerDay'    => 'required|numeric|min:0',
                'amount'         => 'required|numeric|min:0',
                'status'         => 'nullable|in:0,1',
                'notes'          => 'nullable|string',
                'photo.name'     => 'nullable|string',
                'photo.type'     => 'nullable|string',
                'photo.size'     => 'nullable|numeric|max:10485760',
                'photo.base64'   => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $start            = Carbon::parse($req->startDate);
            $end              = Carbon::parse($req->endDate);
            $days             = $start->diffInDays($end) + 1;
            $calculatedAmount = $days * $req->pricePerDay;

            $rentCar = RentCar::create([
                'vehicle_id'     => $req->vehicle_id,
                'renter_name'    => $req->renter_name,
                'renter_address' => $req->renter_address,
                'renter_phone'   => $req->renter_phone,
                'startDate'      => $req->startDate,
                'endDate'        => $req->endDate,
                'pricePerDay'    => $req->pricePerDay,
                'notes'          => $req->notes,
                'status'         => $req->status,
            ]);

            if ($req->filled('photo.base64')) {
                $rentCar->uploadBase64File($req->photo, 'photo', 'rentCar_transactions');
            }

            $rentCar->paymentAmount()->create([
                'amount'        => $calculatedAmount,
                'type'          => PaymentAmount::TYPE_MASUK,
                'status'        => PaymentAmount::STATUS_ACTIVE,
            ]);

            $rentCar->logActivity(
                ActivityLog::ACTION_CREATE,
                "Data Sewa Kendaraan Rental {$rentCar->startDate} berhasil ditambahkan"
            );

            return redirect('/transactions/rentCar/')
                ->with('success', 'Data Sewa Kendaraan Rental berhasil ditambahkan!');
        });
    }

   public function update(Request $req, $rentCarId)
    {
        return $this->executeTransaction(function () use ($req, $rentCarId) {
            $rentCar = RentCar::findOrFail($rentCarId);

            $validator = Validator::make($req->all(), [
                'vehicle_id'     => 'nullable|exists:vehicles,vehicleId',
                'renter_name'    => 'nullable|string|min:3|max:75',
                'renter_address' => 'nullable|string|min:3',
                'renter_phone'   => 'nullable|string|max:20',
                'startDate'      => 'nullable|date',
                'endDate'        => 'nullable|date|after_or_equal:startDate',
                'pricePerDay'    => 'nullable|numeric|min:0',
                'notes'          => 'nullable|string',
                'amount'         => 'nullable|numeric|min:0',
                'photo.name'    => 'nullable|string',
                'photo.type'    => 'nullable|string',
                'photo.size'    => 'nullable|numeric|max:10485760',
                'photo.base64'  => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $rentCar->update([
                'vehicle_id'     => $req->vehicle_id ?? $rentCar->vehicle_id,
                'renter_name'    => $req->renter_name ?? $rentCar->renter_name,
                'renter_address' => $req->renter_address ?? $rentCar->renter_address,
                'renter_phone'   => $req->renter_phone ?? $rentCar->renter_phone,
                'startDate'      => $req->startDate ?? $rentCar->startDate,
                'endDate'        => $req->endDate ?? $rentCar->endDate,
                'pricePerDay'    => $req->pricePerDay ?? $rentCar->pricePerDay,
                'notes'          => $req->notes ?? $rentCar->notes,
            ]);

            $payment = $rentCar->paymentAmount()->first();
            if ($payment) {
                $payment->update(['amount' => $req->amount]);
            } elseif ($req->amount) {
                $rentCar->paymentAmount()->create([
                    'amount' => $req->amount,
                    'type'   => PaymentAmount::TYPE_MASUK,
                    'status' => PaymentAmount::STATUS_ACTIVE,
                ]);
            }

            if ($req->filled('photo.base64')) {
                $rentCar->uploadBase64File($req->photo, 'photo', 'rent_car_photos');
            }

            $rentCar->logActivity(ActivityLog::ACTION_UPDATE, "Data sewa kendaraan tanggal {$rentCar->startDate} berhasil diperbarui");

            return redirect('/transactions/rentCar')->with('success', 'Data sewa kendaraan berhasil diperbarui.');
        });
    }

}
