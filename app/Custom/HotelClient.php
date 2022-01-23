<?php

namespace App\Custom;

use App\Custom\RapidApiClient;

/**
 * Hotel client class
 * Suggestions groups: CITY_GROUP, HOTEL_GROUP, LANDMARK_GROUP, TRANSPORT_GROUP
 */
class HotelClient 
{
    public static function searchByGroup($value, $group, $limit = 0) 
    {
        $value = mb_strtolower($value, 'UTF-8');

        $value = iconv('UTF-8','ASCII//TRANSLIT',$value);

        $response = self::connectClient($value);

        // Get suggestions
        $result = self::getEntitiesByGroup($response, $value, $group);

        // Display only 3 suggesstions
        if($limit > 0)
            $result = array_slice($result, 0, $limit);

        return $result;
    }

    protected static function getEntitiesByGroup($response, $value = '', $group = '') 
    {
        if($value == '') {
            return false;
        }

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

    protected static function connectClient($value) 
    {
        $client = 'hotels4';
        $host = env('RAPIDAPI_HOTEL_HOST');
        $key = env('RAPIDAPI_KEY');

        $rapidApiClient = new RapidApiClient($client, $host, $key);
        $response = $rapidApiClient->curlSetup($value);

        return $response;
    }

}