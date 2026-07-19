<?php

namespace App\Services;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TaskQueryService
{
    /**
     * @return array{q: ?string, status: ?string, priority: ?string, category_id: ?string, due: ?string, sort: string, direction: string}
     */
    public function filtersFrom(Request $request): array
    {
        $sort = $request->string('sort')->toString();
        $direction = strtolower($request->string('direction')->toString());

        return [
            'q' => $request->string('q')->trim()->toString() ?: null,
            'status' => $request->string('status')->toString() ?: null,
            'priority' => $request->string('priority')->toString() ?: null,
            'category_id' => $request->string('category_id')->toString() ?: null,
            'due' => $request->string('due')->toString() ?: null,
            'sort' => in_array($sort, ['due_date', 'priority', 'created_at', 'title'], true) ? $sort : 'created_at',
            'direction' => in_array($direction, ['asc', 'desc'], true) ? $direction : 'desc',
        ];
    }

    public function paginateFor(User $user, Request $request, int $perPage = 10): LengthAwarePaginator
    {
        $filters = $this->filtersFrom($request);

        return $this->apply(
            Task::query()->where('user_id', $user->id)->with('category'),
            $filters
        )
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @param  array{q: ?string, status: ?string, priority: ?string, category_id: ?string, due: ?string, sort: string, direction: string}  $filters
     */
    public function apply(Builder $query, array $filters): Builder
    {
        $this->applySearch($query, $filters['q']);
        $this->applyStatus($query, $filters['status']);
        $this->applyPriority($query, $filters['priority']);
        $this->applyCategory($query, $filters['category_id']);
        $this->applyDuePreset($query, $filters['due']);
        $this->applySort($query, $filters['sort'], $filters['direction']);

        return $query;
    }

    private function applySearch(Builder $query, ?string $search): void
    {
        if ($search === null || $search === '') {
            return;
        }

        $query->where(function (Builder $builder) use ($search): void {
            $builder->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhereHas('category', function (Builder $categoryQuery) use ($search): void {
                    $categoryQuery->where('name', 'like', "%{$search}%");
                });
        });
    }

    private function applyStatus(Builder $query, ?string $status): void
    {
        if ($status === null || ! in_array($status, TaskStatus::values(), true)) {
            return;
        }

        $query->where('status', $status);
    }

    private function applyPriority(Builder $query, ?string $priority): void
    {
        if ($priority === null || ! in_array($priority, TaskPriority::values(), true)) {
            return;
        }

        $query->where('priority', $priority);
    }

    private function applyCategory(Builder $query, ?string $categoryId): void
    {
        if ($categoryId === null || ! ctype_digit($categoryId)) {
            return;
        }

        $query->where('category_id', (int) $categoryId);
    }

    private function applyDuePreset(Builder $query, ?string $due): void
    {
        if ($due === null) {
            return;
        }

        $today = Carbon::today();

        match ($due) {
            'today' => $query->whereDate('due_date', $today),
            'tomorrow' => $query->whereDate('due_date', $today->copy()->addDay()),
            'this_week' => $query->whereBetween('due_date', [
                $today->copy()->startOfWeek(),
                $today->copy()->endOfWeek(),
            ]),
            'this_month' => $query->whereBetween('due_date', [
                $today->copy()->startOfMonth(),
                $today->copy()->endOfMonth(),
            ]),
            default => null,
        };
    }

    private function applySort(Builder $query, string $sort, string $direction): void
    {
        if ($sort === 'priority') {
            $query->orderByRaw(
                "CASE priority
                    WHEN 'urgent' THEN 1
                    WHEN 'high' THEN 2
                    WHEN 'medium' THEN 3
                    WHEN 'low' THEN 4
                    ELSE 5
                END ".$direction
            )->orderByDesc('id');

            return;
        }

        if ($sort === 'due_date') {
            $query->orderByRaw('due_date IS NULL')
                ->orderBy('due_date', $direction)
                ->orderByDesc('id');

            return;
        }

        $query->orderBy($sort, $direction)->orderByDesc('id');
    }
}
