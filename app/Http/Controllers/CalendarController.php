<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function __invoke(Request $request): View
    {
        $view = in_array($request->string('view')->toString(), ['day', 'week', 'month'], true)
            ? $request->string('view')->toString()
            : 'month';

        $date = $this->resolveDate($request->string('date')->toString());

        [$start, $end] = match ($view) {
            'day' => [$date->copy()->startOfDay(), $date->copy()->endOfDay()],
            'week' => [$date->copy()->startOfWeek(Carbon::SUNDAY), $date->copy()->endOfWeek(Carbon::SATURDAY)],
            default => [
                $date->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY),
                $date->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY),
            ],
        };

        $tasksQuery = Task::query()
            ->where('user_id', $request->user()->id)
            ->with('category')
            ->whereNotNull('due_date');

        if ($view === 'day') {
            $tasksQuery->whereDate('due_date', $date->toDateString());
        } else {
            $tasksQuery->whereDate('due_date', '>=', $start->toDateString())
                ->whereDate('due_date', '<=', $end->toDateString());
        }

        $tasks = $tasksQuery
            ->orderBy('due_date')
            ->orderBy('due_time')
            ->get()
            ->groupBy(fn (Task $task) => $task->due_date->format('Y-m-d'));

        $previous = match ($view) {
            'day' => $date->copy()->subDay(),
            'week' => $date->copy()->subWeek(),
            default => $date->copy()->subMonth(),
        };

        $next = match ($view) {
            'day' => $date->copy()->addDay(),
            'week' => $date->copy()->addWeek(),
            default => $date->copy()->addMonth(),
        };

        return view('calendar.index', [
            'view' => $view,
            'date' => $date,
            'start' => $start,
            'end' => $end,
            'tasksByDate' => $tasks,
            'previous' => $previous,
            'next' => $next,
            'days' => $this->daysFor($view, $date, $start, $end),
        ]);
    }

    private function resolveDate(string $value): Carbon
    {
        if ($value !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return Carbon::parse($value)->startOfDay();
        }

        return Carbon::today();
    }

    /**
     * @return list<Carbon>
     */
    private function daysFor(string $view, Carbon $date, Carbon $start, Carbon $end): array
    {
        if ($view === 'day') {
            return [$date->copy()];
        }

        $days = [];
        $cursor = $start->copy()->startOfDay();
        $last = $end->copy()->startOfDay();

        while ($cursor->lte($last)) {
            $days[] = $cursor->copy();
            $cursor->addDay();
        }

        return $days;
    }
}
