<?php

namespace App\Console\Commands;

use App\Custom\HotelClient;
use App\Custom\WeatherClient;
use App\Models\Hotel;
use App\Models\Place;
use App\Models\Weather;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PlaceScheduleExportCommand extends Command
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
        $this->info('Start command!');

        $places = Place::all();

        // Create progress bar
        $bar = $this->output->createProgressBar(count($places));
        $bar->start();
        $i = 1;
        foreach($places as $place) {

            Log::stack(['placeexportlog', 'placeexportdatalog'])->info('--------------------- START: Export hotels and weather for places [Place '.$i++.'/'.count($places).'] -----------------------');
            Log::channel('placeexportlog')->info('Turn on hotels client for place city:'.$place->city);

            // Store hotels using hotel client
            $clientHotels = HotelClient::searchByGroup($place->city, 'HOTEL_GROUP', $limit = 3);
            if(!empty($clientHotels)) {

                Log::channel('placeexportdatalog')->info('City '.$place->city.' hotel client data: '.json_encode($clientHotels));

                foreach($clientHotels as $clientHotel) {
                    $name = $clientHotel['name'];
                    $caption = strip_tags($clientHotel['caption']);
                    $lat = $clientHotel['latitude'];
                    $long = $clientHotel['longitude'];
                    // Check if hotel exists
                    if(!$place->hotels()->haveHotel($name)->exists()) {
                        // Save new hotel
                        $new_hotel = new Hotel;
                        $new_hotel->name = $name;
                        $new_hotel->caption = $caption;
                        $new_hotel->lat = $lat;
                        $new_hotel->long = $long;
                        // Save new hotel assign to current place
                        if($place_hotel = $place->hotels()->save($new_hotel)) {
                            $this->info('The hotel ('. $name .') is stored in city '.$place->city);
                            // Logs
                            Log::channel('placeexportdatalog')->info('SAVED: City '.$place->city.' stored hotel data: '.json_encode($place_hotel));
                            Log::channel('placeexportlog')->info('The hotel ('. $name .') is stored in city '.$place->city);
                        } else {
                            $this->error('The hotel ('. $name .') failed to store in city '.$place->city);

                            Log::channel('placeexportlog')->error('The hotel ('. $name .') failed to store in city '.$place->city);
                        }
                    } else {
                        $this->warn('The hotel ('. $name .') is already stored in city '.$place->city);

                        Log::channel('placeexportlog')->warning('The hotel ('. $name .') is already stored in city '.$place->city);
                    }
                }
            }
            Log::channel('placeexportlog')->info('Turn on weather client for place city: '.$place->city);
            // Store weather using weather client
            $clientWeather = WeatherClient::currentWeather($place->city);

            Log::channel('placeexportdatalog')->info('City '.$place->city.' weather client data: '.json_encode($clientWeather));

            if(isset($clientWeather['list'])) {
                $clientWeatherList = $clientWeather['list'];
                if(empty($clientWeatherList)) {
                    Log::channel('placeexportlog')->error('The weather client list is empty');
                    $this->error('The weather list is empty');
                }
                foreach($clientWeatherList as $list) {
                    $date = \Carbon\Carbon::createFromTimestamp($list['dt'])->format('Y-m-d H:i:s');
                    // First check if wather for current date exists
                    if(!$place->weathers()->todayWeather($list['dt'])->exists()) {
                        // Save new Weather
                        $new_weather = new Weather;
                        $new_weather->api_weather_id = $list['weather'][0]['id'];
                        $new_weather->main = $list['weather'][0]['main'];
                        $new_weather->description = $list['weather'][0]['description'];
                        $new_weather->icon = $list['weather'][0]['icon'];
                        $new_weather->temp_day = $list['temp']['day'];
                        $new_weather->temp_min = $list['temp']['min'];
                        $new_weather->temp_max = $list['temp']['max'];
                        $new_weather->temp_night = $list['temp']['night'];
                        $new_weather->temp_eve = $list['temp']['eve'];
                        $new_weather->temp_morn = $list['temp']['morn'];
                        $new_weather->date = $date;
                        if($place_weather = $place->weathers()->save($new_weather)) {
                            $this->info('The weather for day ('. $date .') is stored in city '.$place->city);
                            // Logs
                            Log::channel('placeexportdatalog')->info('SAVED: City '.$place->city.' stored weather data: '.json_encode($place_weather));
                            Log::channel('placeexportlog')->info('The weather for day ('. $date .') is stored in city '.$place->city);
                        } else {
                            $this->error('The weather for day ('. $date .') failed to stored in city '.$place->city);

                            Log::channel('placeexportlog')->error('The weather for day ('. $date .') failed to stored in city '.$place->city);
                        }
                    } else {
                        $this->warn('The weather for day ('. $date .') is already stored in city '.$place->city);

                        Log::channel('placeexportlog')->warning('The weather for day ('. $date .') is already stored in city '.$place->city);
                    }
                }
            }

            $bar->advance();
            Log::stack(['placeexportlog', 'placeexportdatalog'])->info('--------------------- END: Export hotels and weather for places [Place '.$i.'/'.count($places).'] -----------------------');
        }

        $bar->finish();

        $this->newLine();

        $this->info('End command!');
    }
}
