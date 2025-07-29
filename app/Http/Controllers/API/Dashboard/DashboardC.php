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
use Illuminate\Http\Request;

class DashboardC extends Controller
{
    public function index(Request $request)
    {
        $users       = User::count();
        $branch      = Branch::where('status', Branch::STATUS_ACTIVE)->count();
        $vehicle     = Vehicle::where('status', '!=', Vehicle::STATUS_DELETED)->count();
        $rules       = Rules::first();
        $filterType  = $request->get('filter_type');
        $filterValue = $request->get('filter_value');

        $reportMinDate = PaymentAmount::with('payable')
            ->get()
            ->pluck('payable.report_date')
            ->filter()
            ->min();

        $reportMaxDate = PaymentAmount::with('payable')
            ->get()
            ->pluck('payable.report_date')
            ->filter()
            ->max();

        if (!$filterType || !$filterValue) {
            $startDate   = $reportMinDate ? Carbon::parse($reportMinDate)->startOfMonth() : now()->startOfMonth();
            $endDate     = $reportMaxDate ? Carbon::parse($reportMaxDate)->endOfMonth() : now()->endOfMonth();
            $groupFormat = 'Y-m';
            $labelFormat = 'M Y';
            $range       = collect();
            $cursor      = $startDate->copy();
            while ($cursor <= $endDate) {
                $range->push($cursor->copy());
                $cursor->addMonth();
            }
        } elseif ($filterType === 'year') {
            $year        = (int) $filterValue;
            $startDate   = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endDate     = Carbon::createFromDate($year, 12, 31)->endOfDay();
            $groupFormat = 'Y-m';
            $labelFormat = 'M Y';

            $range = collect();
            $cursor = $startDate->copy();
            while ($cursor <= $endDate) {
                $range->push($cursor->copy());
                $cursor->addMonth();
            }
        } else {
            $date = Carbon::parse($filterValue); 
            $startDate   = $date->copy()->startOfMonth();
            $endDate     = $date->copy()->endOfMonth();
            $groupFormat = 'Y-m-d';
            $labelFormat = 'd M';

            $range  = collect();
            $cursor = $startDate->copy();
            while ($cursor <= $endDate) {
                $range->push($cursor->copy());
                $cursor->addDay();
            }
        }

        $payments = PaymentAmount::with('payable')
            ->get()
            ->filter(function ($payment) use ($startDate, $endDate) {
                $reportDate = optional($payment->payable)->report_date;
                return $reportDate && Carbon::parse($reportDate)->between($startDate, $endDate);
            })
            ->groupBy(function ($payment) use ($groupFormat) {
                return Carbon::parse(optional($payment->payable)->report_date)->format($groupFormat);
            });

        $financeDates      = [];
        $financeIncome     = [];
        $financeExpense    = [];
        $financeProfitLoss = [];

        foreach ($range as $date) {
            $key                 = $date->format($groupFormat);
            $label               = $date->format($labelFormat);
            $dailyPayments       = $payments[$key] ?? collect();
            $income              = $dailyPayments->where('type', PaymentAmount::TYPE_MASUK)->sum('amount');
            $expense             = $dailyPayments->where('type', PaymentAmount::TYPE_KELUAR)->sum('amount');
            $financeDates[]      = $label;
            $financeIncome[]     = $income;
            $financeExpense[]    = $expense;
            $financeProfitLoss[] = $income - $expense;
        }

           $showModal = !$request->session()->has('company_rules_shown');
           $request->session()->put('company_rules_shown', true);

        return view('admin.dashboard.index', [
            'users'             => $users,
            'branch'            => $branch,
            'vehicle'           => $vehicle,
            'rules'             => $rules,
            'financeDates'      => $financeDates,
            'financeIncome'     => $financeIncome,
            'financeExpense'    => $financeExpense,
            'financeProfitLoss' => $financeProfitLoss,
            'showModal'         => $showModal,
        ]);
    }
}
