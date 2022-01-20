<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SchedulePlaceExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export_schedule:hotels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule to export hotels and weather using RapidAPI(hotels4 and community-open-weather-map)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $places = Place::all();
        foreach($places as $place) {
            
        }
    }
}
