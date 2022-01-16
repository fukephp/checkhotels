<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function searchHotels($city)
    {
        $suggestions = $this->findHotelByCity($city);
        return $suggestions;
    }

    protected function findHotelByCity($city)
    {
        $city = strtolower($city);

        $hotels = $this->getEntities($city);

        return $hotels;
    } 

    protected function getEntities($city = '') 
    {
        if($city == '') {
            return false;
        }

        $response = $this->curlSetup($city);

        $suggestions = $this->getSuggestions($response);

        $data = $this->getGroupEntities($suggestions, 'HOTEL_GROUP');

        return $data;
    }

    protected function getGroupEntities($suggestions = [], $group = '')
    {
        $groupArr = [];
        foreach($suggestions as $key => $suggestion) {
            if($suggestion['group'] == $group) {
                $groupArr[] = $suggestion['entities'];
            }
        }
        return $groupArr;
    }

    protected function getSuggestions($response) 
    {
        if(!isset($response['suggestions'])) {
            return false;
        }

        return $response['suggestions'];
    }
    /**
     * TODO: Improve curl client
     * @param  [type] $city [description]
     * @return [type]       [description]
     */
    protected function curlSetup($city = null) 
    {
        if(is_null($city)) {
            return false;
        }

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://hotels4.p.rapidapi.com/locations/v2/search?query={$city}&locale=en_US&currency=USD",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                env('RAPIDAPI_HOST', 'x-rapidapi-host: hotels4.p.rapidapi.com'),
                env('RAPIDAPI_KEY', 'x-rapidapi-key: ab7f802e0amshf8692a3e0dd106dp187dacjsnf1566aa8ff65')
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        $json_arr = [];

        curl_close($curl);

        if ($err) {
            $json_arr['error'] = "cURL Error #:" . $err;
        } else {
            $json_arr = json_decode($response, TRUE);
        }

        return $json_arr;
    }
}
