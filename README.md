# Feed data for places(with hotels, location and weather) Laravel project

## Roadmap 
* [Installation](#Introduction)
* [Laravel project requirements](#laravel-project-requirements)
* [Installation/Configuration](#installation-configuration)
* [Project features](#project-features)

## Introduction :large_orange_diamond:

Laravel project using RapidAPis for scraping data from hosts:
- https://rapidapi.com/apidojo/api/hotels4/
- https://rapidapi.com/community/api/open-weather-map/

## Laravel project requirements :dart:
- **php**: 7.3|8.0v
- **db**: mysql
- **laravel/framework**: 8.75v
- **bootstrap libary**: 4.6.1v
- **jquery libary**: 3.5.1v
- **datatables libary**: 1.11.3v

## Installation/Configuration :open_book: 

- When project is cloned first copy `.env.example` and create `.env` file in root of project.
- Set the application key `php artisan key:generate`
- Update `composer.json` using `composer update`
- Setup database configuration in `.env` file and migrate database `php artisan migrate` 
- Create new user use seed `php artisan seed --class=UserTableSeeder`
- Setup RapidAPI confiugration values in .env file `RAPIDAPI_HOTEL_HOST, RAPIDAPI_WEATHER_HOST, and RAPIDAPI_KEY`
> RAPIDAPI_HOTEL_HOST="x-rapidapi-host: hotels4.p.rapidapi.com"
> RAPIDAPI_WEATHER_HOST="x-rapidapi-host:
> community-open-weather-map.p.rapidapi.com"
> RAPIDAPI_KEY="x-rapidapi-key:
> keyNum"
- Setup Google maps static API
> GOOGLE_MAPS_STATIC_API_KEY="API_KEY"

- Run/Serve the application on the PHP development server use command `php artisan serve` 

## Project features :rocket:
- Login/register user
- Create/Import CSV for places(columns: country, city, and date)
- Export data form with inputs: country, city, date (Select country and city to find suggested three hotels or check daily weather forecast.)
- Hotel list with filter(search by country or city)
- Places table list
- View single place with list of hotels and weather information
- Commands for exporting data (scrape data for hotels and weather and store to database)
- Unit testing project requests(it covers all projects requests)
- Custom logs for export data from RapidAPI (`place_export.log, place_export_data.log and rapid_api.logs`)

## Import CSV file for places :spiral_notepad:
In this page(/import) use form upload csv file that needs to have columns country, city and date. When csv is uploaded use import button to store all data into places table.

## Commands with scheduler :clock6:
When project is setup and places are imported via CSV upload or created manualy there is option to automaticly scrape RapidAPI data from hotels and weather.

Use commands:
To see list of command type in command prompt `php artisan` and find in list of commands:
- `php artisan export_manual:hotels`
- `php artisan export_schedule:hotels` 

### Export manual hotels 
This command is used when you want to select data with conditions:
- Select places with single/all country or city
- Additional store data weather if place have hotels

### Export schedule hotels
This command is used in scheduler it export all data using RapidAPIs for hotels and weather
**Hint** To see scheduler list command use `php artisan schedule:list`
To active scheduler use command `php artisan schedule:run`

## Run the application tests :speech_balloon:
Project have intergated unit tests for all requests in project
Tests are separated in files(`/tests/Feature/*`):
- UserTest
- HotelTest
- ImportTest
- PlaceTest

Run tests using command `php artisan test` or `php artisan test --filter UserTest`

## Logging channels :bookmark:
Laravel provides robust logging services that allow you to log messages to files, the system error log.
In `config/logging.php` there is registered new channels that runs when RapidAPI is used:
- placeexportlog
- placeexportdatalog
- rapidapilog


## Aditional libraries used in project :book:

- Composer packages:
	- glhd/laravel-dumper (https://github.com/glhd/laravel-dumper) version 0.1.0
- Bootstrap framework libary (https://getbootstrap.com/docs/4.6/getting-started/introduction/) version 4.6
- Datatables (https://datatables.net/)
- Google maps API static images (https://developers.google.com/maps/documentation/maps-static/overview)
- Masonry grid layout library https://masonry.desandro.com/

## Project TODOs :building_construction:
- Implement new api endpoint using RapidAPI:
	- https://rapidapi.com/Gramzivi/api/covid-19-data/
- After exporting covid data from RapidAPI create migrations(new table covid_status_counties)
- When migrations are done update places for additional data about country covid status