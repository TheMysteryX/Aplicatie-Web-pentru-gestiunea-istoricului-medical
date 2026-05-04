<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Tratament;
use App\Mail\ReminderTratamentMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class TrimiteReminderTratamente extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:trimite-reminder-tratamente';

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

        $tratamente = Tratament::where('data-sfarsit', $dataMaine)->get();

        foreach ($tratamente as $tratament) {

            $user = $tratament->pacient->user;

            Mail::to($user->email)->send(
                new ReminderTratamentMail($tratament)
            );
        }

        return 0;
    }
}
