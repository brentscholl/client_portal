<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Task;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class TasksChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        sleep(1);
        $tasks = [
            Task::where('status', 'pending')->count(),
            Task::where('status', 'in-progress')->count(),
            Task::where('status', 'completed')->count(),
            Task::where('status', 'on-hold')->count(),
            Task::where('status', 'canceled')->count(),
        ];

        return Chartisan::build()
            ->labels(['Pending', 'In Progress', 'Completed', 'On Hold', 'Canceled'])
            ->dataset('Tasks', $tasks);
    }
}
