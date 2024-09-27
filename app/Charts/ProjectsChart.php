<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Project;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class ProjectsChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        sleep(1);
        $projects = [
            Project::where('status', 'pending')->count(),
            Project::where('status', 'in-progress')->count(),
            Project::where('status', 'completed')->count(),
            Project::where('status', 'on-hold')->count(),
            Project::where('status', 'canceled')->count(),
        ];

        return Chartisan::build()
            ->labels(['Pending', 'In Progress', 'Completed', 'On Hold', 'Canceled'])
            ->dataset('Projects', $projects);
    }
}
