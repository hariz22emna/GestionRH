<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Rappel;
use App\Notifications\rappelStarted;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailTemplateMail;
class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {

        $schedule->call(function () {
            $rappels = Rappel::with('resource')
            ->whereDate('rappelDate', date("Y-m-d"))
            ->where('parentId', '!=', null)
            ->where('isDeleted', 0)
            ->get();
            foreach ($rappels as $rappel) {
                $userId = $rappel->userId;
                $user = User::find($userId);
                if($rappel->typeAlerte != 'Email'){
                    $user->notify(new rappelStarted($rappel));
                }
                if($rappel->typeAlerte != 'Slack'){
                    $contenu = "j'espère que vous allez bien. Je tenais à vous rappeler de l'entretien prévu avec " . $rappel->resource->name . ". Cet entretien est une étape importante du processus d'amélioration des compétences et nous comptons sur votre expertise pour évaluer les talents et l'adéquation de " . $rappel->resource->name . ".";
                    $mailSubject = "Rappel - Entretien avec " . $rappel->resource->name;
                    Mail::to($user->email)->send(new EmailTemplateMail($mailSubject, $contenu, $user->email, ""));
                }
            }
        // })->dailyAt('13:00');
         }) ->everyMinute();

        // get all rappels for now
        // send notification
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}