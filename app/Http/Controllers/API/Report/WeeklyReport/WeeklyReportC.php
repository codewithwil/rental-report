<?php

namespace App\Http\Controllers\API\Report\WeeklyReport;

use App\{
    Http\Controllers\Controller,
    Models\Report\WeeklyReport\WeeklyReport,
    Models\Report\WeeklyReport\WeeklyReportDetail,
    Models\Resources\Company\Company,
    Models\Resources\Vehicle\Vehicle,
    Traits\DbBeginTransac,
    Models\Notification\Notification
};
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\{
    Http\Request,
    Support\Facades\Auth,
    Support\Facades\Validator,
};
use TCPDF;

class WeeklyReportC extends Controller
{
    use DbBeginTransac;
    public function index()
    {
       $user = Auth::user();

    $weeklyReport = WeeklyReport::with(['user', 'vehicle'])
        ->where('status', '!=', WeeklyReport::STATUS_DELETED)
        ->when(!$user->hasRole('admin'), function ($query) use ($user) {
            return $query->where('user_id', $user->id);
        })
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

    public function pdf($weekReportId)
    {
        $weeklyReport = WeeklyReport::with([
            'vehicle', 'weeklyReportDetail',
            'user.admin', 'user.supervisor', 'user.employee'
        ])->findOrFail($weekReportId);

        if (ob_get_length()) ob_end_clean();

        $pdf = new TCPDF();
        $pdf->SetCreator('Rental Mobil');
        $pdf->SetAuthor('Rental Mobil');
        $pdf->SetTitle('Laporan Kondisi Kendaraan Rental');
        $pdf->SetMargins(10, 10, 10);
        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $pdf->SetFont('', 'B', 12);
        $pdf->Cell(0, 10, 'LAPORAN KONDISI KENDARAAN RENTAL', 0, 1, 'C');
        $pdf->Ln(3);
        $pdf->SetFont('', '', 10);

        $info = [
            'Periode Pemeriksaan' => Carbon::parse($weeklyReport->report_date)->format('d F Y'),
            'Nama Kendaraan' => $weeklyReport->vehicle->name,
            'Nomor Polisi' => $weeklyReport->vehicle->plate_number,
            'Tahun Kendaraan' => $weeklyReport->vehicle->year ?? '-',
            'Kilometer Terakhir' => $weeklyReport->vehicle->last_km ?? '-',
            'Pemeriksa' => $weeklyReport->user?->admin?->name
                ?? $weeklyReport->user?->supervisor?->name
                ?? $weeklyReport->user?->employee?->name
        ];

        foreach ($info as $label => $value) {
            $pdf->Cell(50, 6, $label . ' :', 0, 0);
            $pdf->Cell(0, 6, $value, 0, 1);
        }

        $pdf->Ln(3);
        $pdf->SetFont('', 'B');
        $pdf->Cell(0, 6, 'Kondisi Umum Kendaraan', 0, 1);
        $pdf->SetFont('', '', 10);

        $pdf->SetFillColor(255, 255, 0);
        $pdf->SetTextColor(0);
        $pdf->Cell(60, 8, 'Komponen', 1, 0, 'C', true);
        $pdf->Cell(65, 8, 'Status', 1, 0, 'C', true);
        $pdf->Cell(65, 8, 'Keterangan / Catatan', 1, 1, 'C', true);

        $pdf->SetFillColor(255);
        $pdf->SetTextColor(0);

        $displayedComponents = [];

        foreach ($weeklyReport->weeklyReportDetail as $i => $detail) {
            $componentName = ucfirst($detail->component);

            if (in_array($componentName, $displayedComponents)) {
                continue; 
            }

            $displayedComponents[] = $componentName;

            $startX = $pdf->GetX();
            $startY = $pdf->GetY();
            $height = 10;

            // Komponen
            $pdf->Rect($startX, $startY, 60, $height);
            $pdf->SetXY($startX + 2, $startY + 2);
            $pdf->Cell(56, 6, $componentName, 0, 0, 'L');

            // Status
            $pdf->SetXY($startX + 60, $startY);
            $pdf->Rect($startX + 60, $startY, 65, $height);
            $pdf->TextField("status_$i", 63, 6, [], [
                'x' => $startX + 61,
                'y' => $startY + 2,
            ]);

            // Note
            $pdf->SetXY($startX + 125, $startY);
            $pdf->Rect($startX + 125, $startY, 65, $height);
            $pdf->TextField("note_$i", 63, 6, [], [
                'x' => $startX + 126,
                'y' => $startY + 2,
            ]);

            $pdf->SetY($startY + $height);
        }

        $pdf->Ln(5);
        $pdf->SetFont('', 'B');
        $pdf->Cell(0, 6, 'Dokumentasi foto', 0, 1);
        $pdf->SetFont('', '');

        $pdf->MultiCell(0, 6, 'Untuk dokumentasi foto kondisi kendaraan, silakan akses melalui tautan berikut:', 0, 'L');
        $pdf->SetTextColor(0, 0, 255);
        $pdf->Write(6, "Aplikasi Rental mobil", url('/report/weeklyReport/detail/' . $weeklyReport->weekReportId));
        $pdf->SetTextColor(0);

        $pdf->Ln(5);
        $pdf->SetFont('', 'B');
        $pdf->Cell(0, 6, 'Langkah-langkah:', 0, 1);
        $pdf->SetFont('', '');

        $steps = [
            "1. Masuk ke aplikasi menggunakan akun masing-masing",
            "2. Pilih menu “Laporan” di sidebar kiri",
            "3. Klik submenu “Laporan Mingguan”",
            "4. Cari unit dan tanggal yang sesuai dengan nomor polisi: " . $weeklyReport->vehicle->plate_number,
            "5. Klik “Detail” untuk melihat detail dan foto-fotonya"
        ];
        foreach ($steps as $step) {
            $pdf->MultiCell(0, 6, $step, 0, 'L');
        }

        $pdf->Ln(3);
        $pdf->SetFont('', 'B');
        $pdf->MultiCell(0, 6, "Lihat panduan lengkap dalam video tutorial berikut:", 0, 'L');
        $pdf->SetFont('', '');
        $pdf->SetTextColor(0, 0, 255);
        $pdf->Write(6, "Tonton Video Tutorial di Sini", 'https://drive.google.com/file/d/1d0Yc-FRzbPJ7IDfZhtTwdameEJkJVBQX/view?usp=sharing');
        $pdf->SetTextColor(0);

        $pdf->Output('Laporan_Kendaraan_' . $weeklyReport->vehicle->plate_number . '.pdf', 'I');
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

    public function reject($weekReportId, Request $req)
    {
        return $this->executeTransaction(function () use ($req, $weekReportId) {
            try {
                $weekReport = WeeklyReport::findOrFail($weekReportId);
                $weekReport->status = WeeklyReport::STATUS_REJECTED;
                $weekReport->save();

                Notification::create([
                    'user_id' => $weekReport->user_id, 
                    'title'   => 'Laporan Mingguan Ditolak',
                    'message' => $req->note, 
                    'link'    => url('/report/weeklyReport/show/' . $weekReport->weekReportId),
                    'is_read' => false,
                ]);

                return redirect('/report/weeklyReport/')
                    ->with('success', 'Data Laporan Mingguan Ditolak');
            } catch (\Exception $e) {
                return redirect('/report/weeklyReport/')
                    ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        });
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
