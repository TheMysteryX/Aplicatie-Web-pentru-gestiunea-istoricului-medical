<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Reteta;
use App\Mail\ReminderRetetaMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class TrimiteReminderRetete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:trimite-reminder-retete';

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

        $retete = Reteta::where('data_expirare', $dataMaine)->get();

        foreach ($retete as $reteta) {

            $user = $reteta->pacient->user;

            Mail::to($user->email)->send(
                new ReminderRetetaMail($reteta)
            );
        }

        return 0;
    }
}
