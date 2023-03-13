# Feed data for places(with hotels, location and weather) Laravel project

## Roadmap 
* :star: [Introduction](#star-introduction)
* :dart: [Laravel project requirements](#dart-laravel-project-requirements)
* :gear:  [Installation/Configuration](#gear--installationconfiguration)
* :rocket: [Project features](#rocket-project-features)
* :spiral_notepad: [Import CSV file for places](#spiral_notepad-import-csv-file-for-places)
* :clock6: [Commands with scheduler](#spiral_notepad-import-csv-file-for-places)
	* [Export manual hotels](#spiral_notepad-import-csv-file-for-places)
	* [Export schedule hotels](#export-schedule-hotels)
* :speech_balloon: [Run the application tests](#speech_balloon-run-the-application-tests)
* :bookmark: [Logging channels](#bookmark-logging-channels)
* :electric_plug: [Aditional libraries used in project](#electric_plug-aditional-librariespackages-used-in-project)
* :building_construction: [Project TODOs](#building_construction-project-todos)

## :star: Introduction

Laravel project using RapidAPis for scraping data from hosts:
- https://rapidapi.com/apidojo/api/hotels4/
- https://rapidapi.com/community/api/open-weather-map/

## :dart: Laravel project requirements
- **php**: v7.3|v8.0
- **db**: mysql
- **laravel/framework**: v8.75
- **bootstrap libary**:  v4.6.1
- **jquery libary**: v3.5.1
- **datatables libary**: v1.11.3

## :gear:  Installation/Configuration

- When project is cloned first copy `.env.example` and create `.env` file in root of project.
- Update `composer.json` using `composer update`
- Set the application key `php artisan key:generate`
- Setup database configuration in `.env` file and migrate database `php artisan migrate` 
- Create new user use seed `php artisan db:seed --class=UserTableSeeder`
- Setup RapidAPI confiugration values in .env file `RAPIDAPI_HOTEL_HOST, RAPIDAPI_WEATHER_HOST, and RAPIDAPI_KEY`
> RAPIDAPI_HOTEL_HOST=
> RAPIDAPI_WEATHER_HOST=
> RAPIDAPI_KEY=
- Setup Google maps static API
> GOOGLE_MAPS_STATIC_API_KEY="API_KEY"


- Run/Serve the application on the PHP development server use command `php artisan serve` 
- Use csv file that is located `public/csv/placesdata.csv` and register or login with seed user, then in homepage go to Import CSV data for places link and use upload form

## :rocket: Project features
- Login/Register user
- Create/Import CSV for places(columns: country, city, and date)
- Export data form with inputs: country, city, date (Fill all inputs store as place and find suggested three hotels or check daily weather forecast using RapidAPI.)
- Hotel list with filter(search by country or city)
- Places table list
- View single place with list of hotels and weather information
- Commands for exporting data (scrape data for hotels and weather and store to database)
- Unit testing project requests(it covers all projects requests)
- Custom logs for export data from RapidAPI (`place_export.log, place_export_data.log and rapid_api.logs`)

## :spiral_notepad: Import CSV file for places
In this page(/import) use form upload csv file that needs to have columns country, city and date. When csv is uploaded use import button to store all data into places table.

## :clock6: Commands with scheduler
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
**Hint:** To see scheduler list command use `php artisan schedule:list`
To active scheduler use command `php artisan schedule:run`
**Hint:** Schaduler time interval 6 hours when againg command will be executed

## :speech_balloon: Run the application tests
Project have intergated unit tests for all requests in project
Tests are separated in files(`/tests/Feature/*`):
- UserTest.php
- HotelTest.php
- ImportTest.php
- PlaceTest.php

Run tests using command `php artisan test` or `php artisan test --filter UserTest`

## :bookmark: Logging channels
Laravel provides robust logging services that allow you to log messages to files, the system error log.
In `config/logging.php` there is registered new channels that runs when RapidAPI is used:
- placeexportlog (storage/logs/place_export.log)
- placeexportdatalog (storage/logs/place_export_data.log)
- rapidapilog (storage/logs/rapid_api.log)


## :electric_plug: Aditional libraries/packages used in project 

- Composer packages:
	- [glhd/laravel-dumper](https://github.com/glhd/laravel-dumper) v0.1.0
	- [Monarobase Country List](https://github.com/Monarobase/country-list) v3.2
- [Bootstrap CSS framework libary](https://getbootstrap.com/docs/4.6/getting-started/introduction/) version 4.6
- [Datatables](https://datatables.net/)
- [Google maps API static images](https://developers.google.com/maps/documentation/maps-static/overview)
- [Masonry grid layout library](https://masonry.desandro.com/)

## :building_construction: Project TODOs
- :x: Implement new api endpoint using RapidAPI:
	- https://rapidapi.com/Gramzivi/api/covid-19-data/
- :x: After exporting covid data from RapidAPI create migrations(new table covid_status_counties)
- :x: When migrations are done update places for additional data about country covid status
- :white_check_mark: ~~Hotels list with filter~~
- :white_check_mark: ~~Unit test hotels requests~~