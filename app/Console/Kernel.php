<?php

namespace App\Console;

use App\Http\Controllers\OpenExchangeRates;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    
    protected $commands = [];

    protected function schedule(Schedule $schedule)
    {
        $schedule->call(new OpenExchangeRates(new \App\Services\OpenExchangeRatesService(new \App\OpenExchangeRates())));
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
