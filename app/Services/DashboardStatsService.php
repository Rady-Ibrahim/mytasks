<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class DashboardStatsService
{
    /**
     * @return array{
     *     stats: array<string, int|float>,
     *     todayTasks: Collection<int, Task>,
     *     upcomingTasks: Collection<int, Task>,
     *     overdueTasks: Collection<int, Task>,
     *     completedTasks: Collection<int, Task>,
     *     recentActivity: Collection<int, Task>
     * }
     */
    public function for(User $user): array
    {
        $today = Carbon::today();
        $weekStart = $today->copy()->startOfWeek();
        $weekEnd = $today->copy()->endOfWeek();
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();

        $base = Task::query()->where('user_id', $user->id);

        $total = (clone $base)->count();
        $completed = (clone $base)->where('status', TaskStatus::Completed)->count();
        $pending = (clone $base)->where('status', TaskStatus::Pending)->count();
        $inProgress = (clone $base)->where('status', TaskStatus::InProgress)->count();
        $overdue = (clone $base)
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', $today)
            ->whereNotIn('status', [TaskStatus::Completed, TaskStatus::Cancelled])
            ->count();
        $todayCount = (clone $base)->whereDate('due_date', $today)->count();

        $weekTotal = (clone $base)->whereBetween('due_date', [$weekStart, $weekEnd])->count();
        $weekCompleted = (clone $base)
            ->whereBetween('due_date', [$weekStart, $weekEnd])
            ->where('status', TaskStatus::Completed)
            ->count();

        $monthTotal = (clone $base)->whereBetween('due_date', [$monthStart, $monthEnd])->count();
        $monthCompleted = (clone $base)
            ->whereBetween('due_date', [$monthStart, $monthEnd])
            ->where('status', TaskStatus::Completed)
            ->count();

        $completionPercentage = $total > 0 ? round(($completed / $total) * 100, 1) : 0.0;
        $weeklyProgress = $weekTotal > 0 ? round(($weekCompleted / $weekTotal) * 100, 1) : 0.0;
        $monthlyProgress = $monthTotal > 0 ? round(($monthCompleted / $monthTotal) * 100, 1) : 0.0;

        return [
            'stats' => [
                'total' => $total,
                'completed' => $completed,
                'pending' => $pending,
                'in_progress' => $inProgress,
                'overdue' => $overdue,
                'today' => $todayCount,
                'completion_percentage' => $completionPercentage,
                'weekly_progress' => $weeklyProgress,
                'monthly_progress' => $monthlyProgress,
                'week_total' => $weekTotal,
                'week_completed' => $weekCompleted,
                'month_total' => $monthTotal,
                'month_completed' => $monthCompleted,
            ],
            'todayTasks' => (clone $base)
                ->with('category')
                ->whereDate('due_date', $today)
                ->orderBy('due_time')
                ->limit(8)
                ->get(),
            'upcomingTasks' => (clone $base)
                ->with('category')
                ->whereNotNull('due_date')
                ->whereDate('due_date', '>', $today)
                ->whereNotIn('status', [TaskStatus::Completed, TaskStatus::Cancelled])
                ->orderBy('due_date')
                ->limit(8)
                ->get(),
            'overdueTasks' => (clone $base)
                ->with('category')
                ->whereNotNull('due_date')
                ->whereDate('due_date', '<', $today)
                ->whereNotIn('status', [TaskStatus::Completed, TaskStatus::Cancelled])
                ->orderBy('due_date')
                ->limit(8)
                ->get(),
            'completedTasks' => (clone $base)
                ->with('category')
                ->where('status', TaskStatus::Completed)
                ->latest('completed_at')
                ->limit(8)
                ->get(),
            'recentActivity' => (clone $base)
                ->with('category')
                ->latest('updated_at')
                ->limit(8)
                ->get(),
        ];
    }
}
