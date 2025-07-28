<?php

namespace App\Http\Controllers\API\Dashboard;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Branch\Branch,
    Models\User,
    Models\Resources\Rules\Rules,
    Models\Resources\Vehicle\Vehicle,
    Models\Transactions\Payment\PaymentAmount
};

use Carbon\Carbon;

class DashboardC extends Controller
{
    public function index()
    {
        $users     = User::count();
        $branch    = Branch::where('status', Branch::STATUS_ACTIVE)->count();
        $vehicle   = Vehicle::where('status', '!=', Vehicle::STATUS_DELETED)->count();
        $rules     = Rules::first();
        $startDate = Carbon::now()->subMonths(12)->startOfMonth(); 
        $endDate   = Carbon::now()->endOfDay();
        $payments  = PaymentAmount::with('payable')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->groupBy(function ($payment) {
            return Carbon::parse(optional($payment->payable)->report_date ?? $payment->created_at)->format('Y-m');
        });

        $financeDates      = [];
        $financeIncome     = [];
        $financeExpense    = [];
        $financeProfitLoss = [];

        foreach (Carbon::parse($startDate)->startOfMonth()->monthsUntil($endDate) as $month) {
            $formatted           = $month->format('Y-m');
            $label               = $month->format('M Y');
            $monthlyPayments     = $payments[$formatted] ?? collect(); 
            $pemasukan           = $monthlyPayments->where('type', PaymentAmount::TYPE_MASUK)->sum('amount');
            $pengeluaran         = $monthlyPayments->where('type', PaymentAmount::TYPE_KELUAR)->sum('amount');
            $financeDates[]      = $label;
            $financeIncome[]     = $pemasukan;
            $financeExpense[]    = $pengeluaran;
            $financeProfitLoss[] = $pemasukan - $pengeluaran;
        }

        return view('admin.dashboard.index', [
            'users'             => $users,
            'branch'            => $branch,
            'vehicle'           => $vehicle,
            'rules'             => $rules,
            'financeDates'      => $financeDates,
            'financeIncome'     => $financeIncome,
            'financeExpense'    => $financeExpense,
            'financeProfitLoss' => $financeProfitLoss,
        ]);
    }
}
