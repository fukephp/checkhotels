<?php

namespace App\Http\Controllers;

use App\Custom\HotelClient;
use App\Http\Requests\ImportCsvRequest;
use App\Models\Import;
use App\Models\Place;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    /**
     * List all imports
     * @test test_it_user_can_see_import_page
     */
    public function index()
    {
        $imports = Import::all();
        $csvImports = [];
        foreach($imports as $key => $import) {
            $filename = storage_path('/app/public/'.$import->path);
            $file = fopen($filename, "r");
            while ( ($data = fgetcsv($file, 1000, ",")) !==FALSE ) {
                $csvImports[$key][] = $data;
            }
            $import->csvdata = $csvImports[$key];
        }
        return view('import.index', compact('imports'));
    } 

    /**
     * Store csv file in storage/app/public/csv
     * @test test_it_user_can_upload_csv_file_in_import_page
     */
    public function importStore(ImportCsvRequest $request)
    {
        $fileName = $request->file->getClientOriginalName();
        $filePath = $request->file('file')->storeAs('csv', $fileName, 'public');

        $import = Import::create(['name' => $fileName, 'path' => $filePath]);

        if($import) {
            return back()->with('success','Csv File has been uploaded in storage.');
        }
    }

    /**
     * Store places from selected import csv file
     * @test 
     */
    public function storePlaces($id)
    {
        $import = Import::findOrFail($id);
        $filename = storage_path('/app/public/'.$import->path);
        $file = fopen($filename, "r");
        $i=0;
        while ( ($data = fgetcsv($file, 1000, ",")) !==FALSE ) {
            $i++;
            // Dont store first record beacoue they are column name type
            if($i == 1) {
                continue;
            }
            // Create new place
            // Csv columns
            // data[0] -> country, 
            // data[1] -> city, 
            // data[2] -> date, 
            $country = $data[0];
            $city = $data[1];
            $date = $data[2];
            $full_name = $city.', '.$country;
            // Date needs to be formated
            $formated_date = Carbon::createFromFormat('d.m.y', $date)->format('Y-m-d');
            $clientHotels = HotelClient::searchByGroup($full_name, null, 'CITY_GROUP', $limit = 1);
            $destination_id = '';
            $geo_id = '';
            if(!empty($clientHotels)) {
                $destination_id = $clientHotels[0]['destinationId'];
                $geo_id = $clientHotels[0]['geoId'];
            }
            $place = new Place;
            $place->api_destination_id = $destination_id;
            $place->api_geo_id = $geo_id;
            $place->country = $country;
            $place->city = $city;
            $place->date = $formated_date;

            if($place->save()) {
                // Send log
                Log::channel('placeimportlog')->info('Place ('. $place->city .', '.$place->country.') is stored from CSV import');
            }
            
        }
        // After places are created remove csv and import record
        if($import->delete()) {
            unlink($filename);
            // Send log
            Log::channel('placeimportlog')->info('Import '.$filename.' is deleted.');
            return back()->with('success','Places are successfully stored!');
        }

        return back()->with('error', 'Failed!');
    }

    /**
     * Remove import also remove file in storage/app/public/csv/...
     * @test
     */
    public function delete($id)
    {
        $import = Import::findOrFail($id);
        $filename = storage_path('/app/public/'.$import->path);
        // After places are created remove csv and import record
        if($import->delete()) {
            unlink($filename);
            // Send log
            Log::channel('placeimportlog')->info('Import '.$filename.' is deleted.');
            return back()->with('success','Import is removed!');
        }
        return back()->with('error', 'Failed!');

    }  
}
