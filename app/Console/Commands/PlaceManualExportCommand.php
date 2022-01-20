<?php

namespace App\Console\Commands;

use App\Custom\HotelClient;
use App\Custom\WeatherClient;
use App\Models\Hotel;
use App\Models\Place;
use App\Models\Weather;
use Illuminate\Console\Command;

class PlaceManualExportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export_manual:hotels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export hotels and weather using RapidAPI(hotels4 and community-open-weather-map) and store to database';

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
        $countries = Place::all()->pluck('country')->unique()->toArray();
        $cities = Place::all()->pluck('city')->unique()->toArray();
        $implode_countries = implode(', ', $countries);
        $implode_cities = implode(', ', $countries);
        // Step 1
        $choiceGetPlacesWithCondition = $this->choice('Get all places without conditions', ['yes', 'no'], 'yes');
        // Step 2
        if($choiceGetPlacesWithCondition == 'yes') {
            // Step 3
            $choiceWithCondition = $this->choice('Condition by: ', ['country', 'city'], 'country');

            if($choiceWithCondition == 'country') {
                // Step 4: find places with country condition 
                $choiceCountry = $this->choice(
                    'Select cities by country?',
                    $countries, 
                    '');
                if($choiceCountry != '' || is_null($choiceCountry)) {
                    $places = Place::where('country', $choiceCountry)->get();
                }
            } elseif ($choiceWithCondition == 'city') {
                // Step 4: or find places with city condition
                $choiceCity = $this->choice(
                    'Select city?',
                    $cities, 
                    '');
                if($choiceCity != '' || is_null($choiceCity)) {
                    $places = Place::where('city', $choiceCity)->get();
                }
            }
        }

        // Create progress bar
        $barHotel = $this->output->createProgressBar(count($places));
        // Start progress bar
        $barHotel->start();
        foreach($places as $place) {
            $clientHotels = HotelClient::searchByGroup($place->city, 'HOTEL_GROUP', $limit = 3);
            if(!empty($clientHotels)) {
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
                        if($place->hotels()->save($new_hotel)) {
                            $this->info('The hotel ('. $name .') is stored in city '.$place->city);
                            
                        } else {
                            $this->error('The hotel ('. $name .') failed to store in city '.$place->city);
                        }
                    } else {
                        $this->warn('The hotel ('. $name .') is already stored in city '.$place->city);
                    }
                    // Advance progress bar
                    $barHotel->advance();
                }
            }
        }
        // Finish
        $barHotel->finish();

        // Step 5: add weekly weathers data
        $choiceCheckWeather = $this->choice('Also pull weekly weather forecast for selected?', ['yes', 'no'], 'yes');
        if($choiceCheckWeather == 'yes') {
            // Create progress bar
            $barWeather = $this->output->createProgressBar(count($places));
            // Start progress bar
            $barWeather->start();
            foreach($places as $place) {
                $clientWeather = WeatherClient::currentWeather($place->city);
                $clientWeatherList = $clientWeather['list'];
                if(empty($clientWeatherList)) {
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
                        if($place->weathers()->save($new_weather)) {
                            $this->info('The weather for day ('. $date .') is stored in city '.$place->city);
                        } else {
                            $this->error('The weather for day ('. $date .') failed to stored in city '.$place->city);
                        }
                    } else {
                        $this->warn('The weather for day ('. $date .') is already stored in city '.$place->city);
                    }
                    // Advance progress bar
                    $barWeather->advance();
                }
            }
            // Finish
            $barWeather->finish();
        }

        // Write a single blank line...
        $this->newLine();

        $this->info('Command is finished!');

        return true;
    }
}
