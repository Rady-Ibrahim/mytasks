<?php

namespace App\Http\Controllers;

use App\Services\DashboardStatsService;
use App\Services\NotificationDispatchService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(
        Request $request,
        DashboardStatsService $stats,
        NotificationDispatchService $notifications,
    ): View {
        $notifications->syncFor($request->user());

        return view('dashboard.index', $stats->for($request->user()));
    }
}
