<?php

declare(strict_types = 1);

namespace App\Charts;

use App\Models\Login;
use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;

class LoginChart extends BaseChart
{
    /**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        sleep(1);
        $logins = [
            // 2 hours ago
            Login::whereBetween('created_at', [now()->subDays(3), now()->subDays(2)])->count(),
            // 1 hour ago
            Login::whereBetween('created_at', [now()->subDays(2), now()->subDays(1)])->count(),
            // past hour
            Login::whereBetween('created_at', [now()->subDays(1), now()])->count(),
        ];

        return Chartisan::build()
            ->labels(['Two Days Ago', 'Yesterday', 'Today'])
            ->dataset('Logins', $logins);
    }
}
