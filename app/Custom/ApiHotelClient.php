<?php

namespace App\Custom;

/**
 * API class
 */
class ApiHotelClient
{
	public static function searchHotels($city)
    {
        $suggestions = self::findHotelByCity($city);

        // Display only 3 suggesstions
        $suggestions = array_slice($suggestions, 0, 3);

        return $suggestions;
    }

    protected static function findHotelByCity($city)
    {
        $city = strtolower($city);

        $hotels = self::getEntities($city);

        return $hotels;
    } 

    protected static function getEntities($city = '') 
    {
        if($city == '') {
            return false;
        }

        $response = self::curlSetup($city);

        $suggestions = self::getSuggestions($response);

        $data = self::getGroupEntities($suggestions, 'HOTEL_GROUP');

        return $data;
    }

    protected static function getGroupEntities($suggestions = [], $group = '')
    {
        $groupArr = [];
        // Return empty array
        if(!$suggestions) {
        	return $groupArr;
        }
        foreach($suggestions as $key => $suggestion) {
            if($suggestion['group'] == $group) {
                $groupArr = $suggestion['entities'];
            }
        }
        return $groupArr;
    }

    protected static function getSuggestions($response) 
    {
        if(!isset($response['suggestions'])) {
            return false;
        }

        return $response['suggestions'];
    }

    /**
     * Use API from RapidAPI (https://rapidapi.com/apidojo/api/hotels4/)
     * @param  [type] $city [description]
     * @return [type]       [description]
     */
	protected static function curlSetup($city = null) 
    {
        if(is_null($city)) {
            return false;
        }

        $city = rawurlencode($city);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://hotels4.p.rapidapi.com/locations/v2/search?query={$city}&locale=en_US&currency=USD&limit=3",
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