<?php

namespace App\Http\Controllers\API\Report\Kas;

use App\{
    Http\Controllers\Controller,
    Models\Transactions\Payment\PaymentAmount,
    Models\Resources\Company\Company
};

use Illuminate\{
    Http\Request,
    Support\Facades\View
};

use TCPDF;

class KasC extends Controller
{
    public function index()
    {
        $kas = PaymentAmount::with('payable')->get();

        $kas = $kas->sortByDesc(function ($item) {
            return optional($item->payable?->report_date ?? null);
        });
        return view('admin.report.kas.index', compact('kas'));
    }

    public function pdf(Request $request)
    {
        $company = Company::first();
        $month   = $request->input('month');
        $year    = $request->input('year');
        $kas     = PaymentAmount::with('payable')->get();
        $kas     = $kas->filter(function ($item) use ($month, $year) {
            $payable = $item->payable;
            if (!$payable || !method_exists($payable, 'getReportDateAttribute')) return false;

            $reportDate = $payable->report_date ? \Carbon\Carbon::parse($payable->report_date) : null;

            return $reportDate &&
                (!$month || $reportDate->format('m') == $month) &&
                (!$year || $reportDate->format('Y') == $year);
        });

        $kas         = $kas->sortByDesc(fn($item) => optional($item->payable?->report_date));
        $totalMasuk  = $kas->where('type', 1)->sum('amount');
        $totalKeluar = $kas->where('type', 2)->sum('amount');
        $totalSemua  = $kas->sum('amount');
        $html        = View::make('admin.report.kas.pdf', compact('kas', 'totalMasuk', 'totalKeluar', 'totalSemua', 'company'))->render();

        if (ob_get_length()) {
            ob_end_clean();
        }

        if (headers_sent()) {
            die('Header sudah terkirim. PDF tidak bisa dibuat.');
        }

        $pdf = new TCPDF();
        $pdf->setPrintHeader(false);
        $pdf->SetCreator('Laporan Keuangan');
        $pdf->SetAuthor('Rental System');
        $pdf->SetTitle('Laporan Keuangan Kendaraan Rental');
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->Output("laporan_kas_{$month}_{$year}.pdf", 'I'); 
    }

}
