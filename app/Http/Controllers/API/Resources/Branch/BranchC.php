<?php

namespace App\Http\Controllers\API\Resources\Branch;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Branch\Branch,
    Models\Resources\Company\Company,
    Traits\DbBeginTransac,
};
use Illuminate\{
    Http\Request,
    Support\Facades\DB,
    Support\Facades\Validator
};

class BranchC extends Controller
{
    use DbBeginTransac;
    public function index()
    {
        $branch = Branch::where('status', Branch::STATUS_ACTIVE)
                        ->orderBy('created_at', 'asc')
                        ->get();

        return view('admin.resources.branch.index', compact('branch'));
    }


    public function invoice(){
        $branch    = Branch::with('picUser')->where('status', Branch::STATUS_ACTIVE)->orderBy('created_at', 'asc')->get();
        $company = Company::first();
        return view('admin.resources.branch.invoice', compact('branch', 'company'));
    }

    public function create()
    {
        $company = Company::first();
        return view('admin.resources.branch.create', compact('company'));
    }

    public function edit($branchId)
    {
        $company = Company::first();
        $branch  = Branch::findOrFail($branchId);
        return view('admin.resources.branch.update', compact('branch', 'company'));
    }

    public function nearest(Request $request)
    {
        $userLat = $request->latitude;
        $userLng = $request->longitude;

        $branches = Branch::where('status', 1)->get();

        $nearest = null;
        $minDistance = INF;

        foreach ($branches as $branch) {
            $distance = $this->haversine($userLat, $userLng, $branch->ltd, $branch->lng);
            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearest = $branch;
            }
        }

        return response()->json([
            'branch' => $nearest,
            'distance_km' => round($minDistance, 2)
        ]);
    }

    public function distanceToBranch(Request $request)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
            'branch_id' => 'required|exists:branches,branchId',
        ]);

        $userLat = $request->latitude;
        $userLng = $request->longitude;

        $branch = Branch::findOrFail($request->branch_id);

        $distance = $this->haversine($userLat, $userLng, $branch->ltd, $branch->lng);

        return response()->json([
            'branch_id' => $branch->branchId,
            'distance_km' => round($distance, 2),
        ]);
    }


    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; 
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon/2) * sin($dLon/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earthRadius * $c;
    }

    public function store(Request $req)
    {
        return $this->executeTransaction(function () use ($req) {
            $validator = Validator::make($req->all(), [
                'company_id'        => 'required|exists:companies,companyId',
                'address'           => 'required|string|min:3|max:255',
                'email'             => 'required|email|max:75|unique:branches,email',
                'operationalHours'  => 'required|string|min:1|max:50',
                'phone'             => 'required|digits_between:12,15',
                'ltd'               => 'nullable|numeric',
                'lng'               => 'nullable|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }
            
            Branch::create([
                'company_id'       => $req->input('company_id'),
                'address'          => $req->input('address'),
                'email'            => $req->input('email'),
                'operationalHours' => $req->input('operationalHours'),
                'phone'            => $req->input('phone'),
                'ltd'              => $req->input('ltd'),
                'lng'              => $req->input('lng'),
            ]);

            return redirect('/setting/branch/')->with('success', 'Cabang berhasil ditambahkan!');
        });
    }

    public function update(Request $req, $branchId)
    {
        $req->validate([
            'company_id'        => 'nullable|exists:companies,companyId',
            'address'           => 'nullable|string|min:3|max:255',
            'email'             => 'nullable|email|max:75|unique:branches,email',
            'operationalHours'  => 'nullable|string|min:1|max:50',
            'phone'             => 'nullable|digits_between:6,15',
            'ltd'               => 'nullable|numeric',
            'lng'               => 'nullable|numeric',
        ]);

        return $this->executeTransaction(function () use ($req, $branchId) {
            $branch = Branch::findOrFail($branchId);
            $branch->company_id       = $req->company_id;
            $branch->address          = $req->addressEdit;
            $branch->email            = $req->emailEdit;
            $branch->operationalHours = $req->operationalHoursEdit;
            $branch->phone            = $req->phoneEdit;
            $branch->ltd              = $req->ltdEdit;
            $branch->lng              = $req->lngEdit;

            $branch->save();

            return redirect('/setting/branch/')->with('success', 'Data cabang berhasil diperbarui.');
        });
    }


    public function delete($branchId) {
        DB::beginTransaction();  
    
        try {
            $branch         = Branch::findOrFail($branchId);
            $branch->status = Branch::STATUS_INACTIVE;
            $branch->save();
            DB::commit();  
    
            $message = 'Data Cabang Berhasil Dihapus';  
            return redirect('/setting/branch/')
                ->with('success', $message);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();  
    
            return redirect('/setting/branch/')
                ->with('error', 'Cabang tidak ditemukan');
        } catch (\Exception $e) {
            DB::rollBack();
    
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
