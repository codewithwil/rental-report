<?php

namespace App\Http\Controllers\API\Dashboard;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Branch\Branch,
    Models\User,
    Models\Resources\Rules\Rules,
    Models\Resources\Vehicle\Vehicle
};
use App\Models\Report\WeeklyReport\WeeklyReport;
use Carbon\Carbon;

class DashboardC extends Controller
{
    public function index() {
        $users    = User::count();
        $branch   = Branch::where('status', Branch::STATUS_ACTIVE)->count();
        $vehicle  = Vehicle::where('status', '!=', Vehicle::STATUS_DELETED)->count();
        $rules    = Rules::first();

        $startDate = Carbon::now()->subDays(30)->startOfDay();
        $endDate   = Carbon::now()->endOfDay();

        $reports = WeeklyReport::selectRaw('DATE(report_date) as date, status, COUNT(*) as total')
            ->whereBetween('report_date', [$startDate, $endDate])
            ->groupBy('date', 'status')
            ->orderBy('date')
            ->get()
            ->groupBy('date');

        $chartData = [];
        $dates = collect();

        foreach ($reports as $date => $items) {
            $dates->push($date);
            $chartData[$date] = [
                'pending'  => 0,
                'approve'  => 0,
                'rejected' => 0,
            ];
            foreach ($items as $item) {
                if ($item->status == WeeklyReport::STATUS_PENDING) {
                    $chartData[$date]['pending'] = $item->total;
                } elseif ($item->status == WeeklyReport::STATUS_APPROVE) {
                    $chartData[$date]['approve'] = $item->total;
                } elseif ($item->status == WeeklyReport::STATUS_REJECTED) {
                    $chartData[$date]['rejected'] = $item->total;
                }
            }
        }

        return view('admin.dashboard.index', [
            'users'      => $users,
            'branch'     => $branch,
            'vehicle'    => $vehicle,
            'rules'      => $rules,
            'chartDates' => $dates->values(),
            'chartData'  => $chartData,
        ]);
    }
}
