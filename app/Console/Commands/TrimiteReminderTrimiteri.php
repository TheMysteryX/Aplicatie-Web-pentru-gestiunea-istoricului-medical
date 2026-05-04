<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Trimitere;
use App\Mail\ReminderTrimitereMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class TrimiteReminderTrimiteri extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:trimite-reminder-trimiteri';

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

        $trimiteri = Trimitere::where('data_expirare', $dataMaine)->get();

        foreach ($trimiteri as $trimitere) {

            $user = $trimitere->pacient->user;

            Mail::to($user->email)->send(
                new ReminderTrimitereMail($trimitere)
            );
        }

        return 0;
    }
}
