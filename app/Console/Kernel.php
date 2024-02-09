<?php

namespace App\Console;

use App\Http\Controllers\Api\Auth\QntrlController;
use App\Models\QntrlCard;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
{

    $schedule->command('zoauth:refresh')->everyThirtyMinutes();
    $schedule->command('zoauth:prune')->daily();
    $schedule->command('model:prune')->weekly();

    $schedule->call(function () {
        $scheduled_tasks = QntrlCard::where('scheduled_time', '<=', now())->whereNotIn('status_updated', ['updated'])->get();
        // dd($scheduled_tasks);
        foreach ($scheduled_tasks as $task) {

            // Update Qntrl Card
            $qntrlId = $task->qntrl_id;
            $updateStatus = new QntrlController();
            $orderStatus = $updateStatus->QntrlScheduler($qntrlId);
        }

    })->everyMinute();
}

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
