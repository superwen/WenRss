<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\StaffWeekly;
use Carbon\Carbon;

class WeeklyController extends Controller
{
    public function index(Request $request)
    {
        $week1 = Carbon::now()->subDays(10);
        $week2 = Carbon::now()->subDays(3);
        $staffs = Staff::where('status', 0)->get();
        $weekly1s = StaffWeekly::where('week', $week1->format('W'))->get()->pluck('staffId')->toArray();
        $weekly2s = StaffWeekly::where('week', $week2->format('W'))->get()->pluck('staffId')->toArray();
        return view('weekly', [
            'staffs' => $staffs,
            'weekly1s' => $weekly1s,
            'weekly2s' => $weekly2s,
            'week1' => $week1,
            'week2' => $week2,
        ]);
    }
}
