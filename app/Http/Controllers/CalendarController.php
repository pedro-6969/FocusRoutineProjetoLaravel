<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    public function show(Request $request)
    {
        $monthParameter = $request->query('month');

        try {
            $currentMonth = $monthParameter
                ? Carbon::createFromFormat('Y-m', $monthParameter)->startOfMonth()
                : now()->startOfMonth();
        } catch (\Throwable $exception) {
            $currentMonth = now()->startOfMonth();
        }

        $calendarStart = $currentMonth
            ->copy()
            ->startOfMonth()
            ->startOfWeek(Carbon::SUNDAY);

        $calendarEnd = $currentMonth
            ->copy()
            ->endOfMonth()
            ->endOfWeek(Carbon::SATURDAY);

        $dateParameter = $request->query('date');

        try {
            if ($dateParameter) {
                $selectedCarbon = Carbon::createFromFormat(
                    'Y-m-d',
                    $dateParameter
                )->startOfDay();
            } elseif ($currentMonth->isSameMonth(now())) {
                $selectedCarbon = now()->startOfDay();
            } else {
                $selectedCarbon = $currentMonth->copy()->startOfDay();
            }
        } catch (\Throwable $exception) {
            $selectedCarbon = $currentMonth->copy()->startOfDay();
        }

        if (
            $selectedCarbon->lt($calendarStart)
            || $selectedCarbon->gt($calendarEnd)
        ) {
            $selectedCarbon = $currentMonth->copy()->startOfDay();
        }

        $selectedDate = $selectedCarbon->format('Y-m-d');

        $tasks = Auth::user()
            ->task()
            ->with('category')
            ->where('status', 'Pending')
            ->whereBetween('task_date', [
                $calendarStart->toDateString(),
                $calendarEnd->toDateString(),
            ])
            ->orderByRaw("
                CASE priority
                    WHEN 'High' THEN 1
                    WHEN 'Medium' THEN 2
                    WHEN 'Low' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('task_time')
            ->get();

        $tasksByDate = $tasks->groupBy(function ($task) {
            return Carbon::parse($task->task_date)->format('Y-m-d');
        });

        $selectedTasks = $tasksByDate->get(
            $selectedDate,
            collect()
        );

        $days = [];

        $day = $calendarStart->copy();

        while ($day->lte($calendarEnd)) {
            $days[] = $day->copy();
            $day->addDay();
        }

        $previousMonth = $currentMonth
            ->copy()
            ->subMonth()
            ->format('Y-m');

        $nextMonth = $currentMonth
            ->copy()
            ->addMonth()
            ->format('Y-m');

        $weekDays = [
            'Dom',
            'Seg',
            'Ter',
            'Qua',
            'Qui',
            'Sex',
            'Sáb',
        ];

        return view('calendar', compact(
            'currentMonth',
            'calendarStart',
            'calendarEnd',
            'selectedCarbon',
            'selectedDate',
            'selectedTasks',
            'tasksByDate',
            'days',
            'previousMonth',
            'nextMonth',
            'weekDays'
        ));
    }
}