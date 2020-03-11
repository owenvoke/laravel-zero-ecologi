<?php

namespace App\Commands;

use Illuminate\Support\Facades\Http;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class StatsCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'stats {username}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Retrieve the Offset Earth statistics for a user';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = $this->argument('username');

        $response = Http::get("https://public.offset.earth/users/{$username}/impact");

        if (! $response->ok()) {
            $this->warn('Failed to retrieve user statistics');
            return 1;
        }

        ['trees' => $trees, 'carbonOffset' => $carbonOffset] = $response->json();

        $this->info("@{$username} has planted {$trees} trees, and offset {$carbonOffset} tonnes of CO2");
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
