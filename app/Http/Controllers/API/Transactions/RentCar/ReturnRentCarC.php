<?php

namespace App\Http\Controllers\API\Transactions\RentCar;

use App\{
    Http\Controllers\Controller,
    Models\History\ActivityLog\ActivityLog,
    Models\Resources\Company\Company,
    Models\Transactions\Payment\PaymentAmount,
    Models\Transactions\RentCar\RentCar,
    Models\Transactions\RentCar\ReturnRentCar,
    Traits\DbBeginTransac
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};

use Carbon\Carbon;

class ReturnRentCarC extends Controller
{
    use DbBeginTransac;
    public function index()
    {
        $rentCar = ReturnRentCar::with(['rentCar.vehicle'])->get();
        return view('admin.transactions.returnRentCar.index', compact('rentCar'));
    }

    public function invoice() 
    {
        $rentCar = ReturnRentCar::get();
        $company = Company::first();
        return view('admin.transactions.returnRentCar.invoice', compact('rentCar', 'company'));
    }

    public function create()
    {
        $rentCar = RentCar::where('status', RentCar::STATUS_ONRENT)
                ->get();
        return view('admin.transactions.returnRentCar.create', compact('rentCar'));
    }

    public function show($returnRentCId){
        $rentCar = ReturnRentCar::with(['vehicle','paymentAmount', 'photo'])
                                                ->findOrFail($returnRentCId);
        return view('admin.transactions.returnRentCar.details', compact('rentCar'));
    }

    public function edit($returnRentCId)
    {
        $returnRentCar = ReturnRentCar::findOrFail($returnRentCId);
        $rentCar       = RentCar::where('status', RentCar::STATUS_ONRENT)
                        ->orWhere('rentCarId', $returnRentCar->rentCar_id)
                        ->get();
        return view('admin.transactions.returnRentCar.update', compact('returnRentCar','rentCar'));
    }

    public function store(Request $req)
    {
        return $this->executeTransaction(function () use ($req) {
            $validator = Validator::make($req->all(), [
                'rentCar_id'     => 'required|exists:rent_cars,rentCarId',
                'return_date'    => 'required|date',
                'return_name'    => 'required|string|min:3|max:75',
                'return_address' => 'required|string|min:3',
                'return_phone'   => 'required|numeric|min:0',
                'notes'          => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $rentCar = ReturnRentCar::create([
                'rentCar_id'     => $req->rentCar_id,
                'return_date'    => $req->return_date,
                'return_name'    => $req->return_name,
                'return_address' => $req->return_address,
                'return_phone'   => $req->return_phone,
                'notes'          => $req->notes,
            ]);

            RentCar::where('returnRentCId', $req->rentCar_id)
                ->update(['status' => RentCar::STATUS_KEMBALI]);

            $rentCar->logActivity(
                ActivityLog::ACTION_CREATE,
                "Data Pengembalian Kendaraan Rental Tanggal {$rentCar->return_date} berhasil ditambahkan"
            );

            return redirect('/transactions/returnRentCar/')
                ->with('success', 'Data Pengembalian Kendaraan Rental berhasil ditambahkan!');
        });
    }


    public function update(Request $req, $returnRentCId)
    {
        return $this->executeTransaction(function () use ($req, $returnRentCId) {
            $returnRentCar = ReturnRentCar::findOrFail($returnRentCId);

            $validator = Validator::make($req->all(), [
                'rentCar_id'     => 'required|exists:rent_cars,rentCarId',
                'return_date'    => 'required|date',
                'return_name'    => 'required|string|min:3|max:75',
                'return_address' => 'required|string|min:3',
                'return_phone'   => 'required|numeric|min:0',
                'notes'          => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            if ($req->rentCar_id != $returnRentCar->rentCar_id) {
                RentCar::where('rentCarId', $returnRentCar->rentCar_id)
                    ->update(['status' => RentCar::STATUS_ONRENT]);

                RentCar::where('rentCarId', $req->rentCar_id)
                    ->update(['status' => RentCar::STATUS_KEMBALI]);
            }

            $returnRentCar->update([
                'rentCar_id'     => $req->rentCar_id,
                'return_date'    => $req->return_date,
                'return_name'    => $req->return_name,
                'return_address' => $req->return_address,
                'return_phone'   => $req->return_phone,
                'notes'          => $req->notes,
            ]);

            $returnRentCar->logActivity(
                ActivityLog::ACTION_UPDATE,
                "Data Pengembalian Kendaraan Rental tanggal {$returnRentCar->return_date} berhasil diperbarui"
            );

            return redirect('/transactions/returnRentCar')
                ->with('success', 'Data Pengembalian Kendaraan Rental berhasil diperbarui!');
        });
    }


}
