<?php

namespace App\Http\Controllers\API\Report\WeeklyReport;

use App\{
    Http\Controllers\Controller,
    Models\Report\WeeklyReport\WeeklyReport,
    Models\Report\WeeklyReport\WeeklyReportDetail,
    Models\Resources\Company\Company,
    Models\Resources\Vehicle\Vehicle,
    Traits\DbBeginTransac,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Auth,
    Support\Facades\Validator,
};

class WeeklyReportC extends Controller
{
    use DbBeginTransac;
    public function index(){
        $weeklyReport = WeeklyReport::with(['user', 'vehicle'])
                            ->where('status', '!=', WeeklyReport::STATUS_DELETED)
                            ->orderBy('created_at', 'asc')
                            ->get();
        return view('admin.report.weeklyReport.index', compact('weeklyReport'));
    }

    public function invoice(){
        $weeklyReport = WeeklyReport::orderBy('created_at', 'asc')->get();
        $company  = Company::first();
        return view('admin.report.weeklyReport.invoice', compact('weeklyReport', 'company'));
    }

    public function create(){
        $vehicle = Vehicle::where('status', '!=', Vehicle::STATUS_DELETED)->get();
        return view('admin.report.weeklyReport.create', compact('vehicle'));
    }

    public function show($weeklyReport){
        $weeklyReport = WeeklyReport::with(['user', 'vehicle', 'weeklyReportDetail'])->findOrFail($weeklyReport);
        return view('admin.report.weeklyReport.details', compact('weeklyReport'));
    }

    public function approve($weekReportId){
        try {
            $weekReport = WeeklyReport::findOrFail($weekReportId);
            $weekReport->status = WeeklyReport::STATUS_APPROVE;
            $weekReport->save();

            return redirect('/report/weeklyReport/')
                ->with('success', 'Data Laporan Mingguan Disetujui');
        } catch (\Exception $e) {
            return redirect('/report/weeklyReport/')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

      public function reject($weekReportId){
        try {
            $weekReport = WeeklyReport::findOrFail($weekReportId);
            $weekReport->status = WeeklyReport::STATUS_REJECTED;
            $weekReport->save();

            return redirect('/report/weeklyReport/')
                ->with('success', 'Data Laporan Mingguan Ditolak');
        } catch (\Exception $e) {
            return redirect('/report/weeklyReport/')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($weeklyReport){
        $vehicle = Vehicle::where('status', '!=', Vehicle::STATUS_DELETED)->get();
        $weeklyReport = WeeklyReport::with('weeklyReportDetail')->findOrFail($weeklyReport);
        return view('admin.report.weeklyReport.update', compact('weeklyReport', 'vehicle'));
    }

    public function store(Request $req)
    {
        return $this->executeTransaction(function () use ($req) {
            $validator = Validator::make($req->all(), [
                'vehicle_id'  => 'required|exists:vehicles,vehicleId',
                'report_date' => 'required|date',
                'note'        => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $userId = Auth::id();
            $weeklyReport = WeeklyReport::create([
                'user_id'     => $userId,
                'vehicle_id'  => $req->input('vehicle_id'),
                'report_date' => $req->input('report_date'),
                'note'        => $req->input('note'),
            ]);

          if ($req->has('details')) {
            foreach ($req->input('details') as $component => $positions) {
                foreach ($positions as $position => $fileJsonString) {
                    $fileData = json_decode($fileJsonString, true);

                    if (isset($fileData['data'], $fileData['type'], $fileData['name'])) {
                        $decodedFile = base64_decode($fileData['data']);
                        $extension = explode('/', $fileData['type'])[1];
                        $fileName = uniqid() . '.' . $extension;
                        $folder = "weekly-report/$component";
                        $path = storage_path("app/public/$folder");

                        if (!file_exists($path)) {
                            mkdir($path, 0755, true);
                        }

                        file_put_contents("$path/$fileName", $decodedFile);

                        WeeklyReportDetail::create([
                            'weekReport_id'    => $weeklyReport->weekReportId,
                            'component'        => $component,
                            'position'         => $position,
                            'file_path'        => "$folder/$fileName",
                            'file_type'        => $fileData['type'],
                        ]);
                    }
                }
            }
        }


            return redirect('report/weeklyReport')->with('success', 'Laporan Mingguan berhasil disimpan.');
        });
    }

    public function update(Request $req, $weekReportId)
    {
        return $this->executeTransaction(function () use ($req, $weekReportId) {
            $validator = Validator::make($req->all(), [
                'vehicle_id'  => 'required|exists:vehicles,vehicleId',
                'report_date' => 'required|date',
                'note'        => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $weeklyReport = WeeklyReport::findOrFail($weekReportId);

            $weeklyReport->update([
                'vehicle_id'  => $req->input('vehicle_id'),
                'report_date' => $req->input('report_date'),
                'note'        => $req->input('note'),
            ]);

            WeeklyReportDetail::where('weekReport_id', $weeklyReport->weekReportId)->delete();
            if ($req->has('details')) {
                foreach ($req->input('details') as $component => $positions) {
                    foreach ($positions as $position => $fileJsonString) {
                        $fileData = json_decode($fileJsonString, true);

                        if (isset($fileData['data'], $fileData['type'], $fileData['name'])) {
                            $decodedFile = base64_decode($fileData['data']);
                            $extension = explode('/', $fileData['type'])[1];
                            $fileName = uniqid() . '.' . $extension;
                            $folder = "weekly-report/$component";
                            $path = storage_path("app/public/$folder");

                            if (!file_exists($path)) {
                                mkdir($path, 0755, true);
                            }

                            file_put_contents("$path/$fileName", $decodedFile);

                            WeeklyReportDetail::create([
                                'weekReport_id' => $weeklyReport->weekReportId,
                                'component'     => $component,
                                'position'      => $position,
                                'file_path'     => "$folder/$fileName",
                                'file_type'     => $fileData['type'],
                            ]);
                        }
                    }
                }
            }

            return redirect('/report/weeklyReport')
                ->with('success', 'Laporan Mingguan berhasil diperbarui.');
        });
    }


    public function delete($weekReportId)
    {
        try {
            $weekReport = WeeklyReport::findOrFail($weekReportId);
            $weekReport->status = WeeklyReport::STATUS_DELETED;
            $weekReport->save();

            return redirect('/report/weeklyReport/')
                ->with('success', 'Data Laporan Mingguan Berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect('/report/weeklyReport/')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
