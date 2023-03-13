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
        $this->info('Start command!');

        $places = Place::all();
        $countries = Place::all()->pluck('country')->unique()->toArray();
        $cities = Place::all()->pluck('city')->unique()->toArray();
        $implode_countries = implode(', ', $countries);
        $implode_cities = implode(', ', $countries);
        // Step 1
        $choiceGetPlacesWithCondition = $this->choice('Get all places with conditions', ['yes', 'no'], 'yes');
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
            $clientHotels = HotelClient::searchByGroup($place->api_destination_id, $place->date->format('Y-m-d'), 'HOTEL_GROUP', $limit = 3);
            if(!empty($clientHotels)) {
                foreach($clientHotels as $clientHotel) {
                    $name = $clientHotel['name'];
                    // Check if hotel exists
                    if(!$place->hotels()->haveHotel($name)->exists()) {
                        // Save new hotel
                        $new_hotel = new Hotel;
                        $new_hotel->api_hotel_id = $clientHotel['api_hotel_id'];
                        $new_hotel->name = $name;
                        $new_hotel->price = $clientHotel['price'];
                        $new_hotel->guest_review_txt = $clientHotel['guest_review_txt'];
                        $new_hotel->guest_review_num = $clientHotel['guest_review_num'];
                        $new_hotel->star_rating = $clientHotel['star_rating'];
                        $new_hotel->lat = $clientHotel['lat'];
                        $new_hotel->long = $clientHotel['long'];
                        $new_hotel->street_address = $clientHotel['street_address'];
                        $new_hotel->extended_address = $clientHotel['extended_address'];
                        $new_hotel->locality = $clientHotel['locality'];
                        $new_hotel->postal_code = $clientHotel['postal_code'];
                        $new_hotel->region = $clientHotel['region'];
                        $new_hotel->country_name = $clientHotel['country_name'];
                        $new_hotel->country_code = $clientHotel['country_code'];
                        $new_hotel->thumbnail_url = $clientHotel['thumbnail_url'];
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
                if(isset($clientWeather['list'])) {
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
            }
            // Finish
            $barWeather->finish();
        }

        // Write a single blank line...
        $this->newLine();

        $this->info('End command!');

        return true;
    }
}
