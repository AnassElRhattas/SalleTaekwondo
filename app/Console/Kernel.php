<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('reminders:send-subscription')
                 ->daily()
                 ->at('09:00')
                 ->appendOutputTo(storage_path('logs/reminders.log'));
                 
        $schedule->command('whatsapp:generate-csv')
                 ->daily()
                 ->at('08:00')
                 ->appendOutputTo(storage_path('logs/whatsapp_csv.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}