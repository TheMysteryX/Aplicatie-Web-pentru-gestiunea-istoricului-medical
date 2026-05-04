<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Programare;
use App\Mail\ReminderProgramareMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class TrimiteReminderProgramari extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:trimite-reminder-programari';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $dataMaine = Carbon::tomorrow()->toDateString();

        $programari = Programare::where('data', $dataMaine)->get();

        foreach ($programari as $programare) {

            $user = $programare->pacient->user;

            Mail::to($user->email)->send(
                new ReminderProgramareMail($programare)
            );
        }

        return 0;
    }
}
