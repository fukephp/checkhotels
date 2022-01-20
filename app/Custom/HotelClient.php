<?php

namespace App\Custom;

/**
 * Hotel client class
 * Suggestions groups: CITY_GROUP, HOTEL_GROUP, LANDMARK_GROUP, TRANSPORT_GROUP
 */
class HotelClient
{
    public static function searchByGroup($value, $group) 
    {
        $value = strtolower($value);

        $result = self::getEntitiesByGroup($value, $group);

        return $result;
    }
    /**
     * Search hotels by group 'HOTEL_GROUP'
     * @param  [type] $value [description]
     * @return [type]        [description]
     */
	public static function searchHotels($value, $group)
    {
        $suggestions = self::getEntitiesByGroup($value, $group);

        // Display only 3 suggesstions
        $suggestions = array_slice($suggestions, 0, 3);

        return $suggestions;
    }

    protected static function findHotelByCity($value)
    {
        $value = strtolower($value);

        $group = 'HOTEL_GROUP';

        $hotels = self::getEntitiesByGroup($value, $group);

        return $hotels;
    }

    protected static function getEntitiesByGroup($value = '', $group = '') 
    {
        if($value == '') {
            return false;
        }

        $response = self::curlSetup($value);

        $suggestions = self::getSuggestions($response);

        $data = self::getGroupEntities($suggestions, $group);

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
     * @param  [type] $value [description]
     * @return [type]       [description]
     */
	protected static function curlSetup($value = null) 
    {
        if(is_null($value)) {
            return false;
        }

        $value = rawurlencode($value);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://hotels4.p.rapidapi.com/locations/v2/search?query={$value}&locale=en_US&currency=USD&limit=3",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                env('RAPIDAPI_HOTEL_HOST', 'x-rapidapi-host: hotels4.p.rapidapi.com'),
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