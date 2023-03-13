<?php

namespace App\Custom;

use App\Custom\RapidApiClient;

/**
 * Hotel client class
 * Suggestions groups: CITY_GROUP, HOTEL_GROUP, LANDMARK_GROUP, TRANSPORT_GROUP
 */
class HotelClient 
{
    public static function searchByGroup($value, $check_in_date = null, $group = null, $limit = 0) 
    {
        $response = self::connectClient($value, $check_in_date);

        // Response data when HOTEL_GROUP is selected
        if(isset($response['result'])) {
            if($response['result'] == 'OK') {
                if(!empty($response['data'])) {
                    $result = $response['data']['body']['searchResults']['results'];
                    $result = self::newResultCollection($result);
                    return $result;
                }
            }
        }
        
        // Get suggestions
        $result = self::getEntitiesByGroup($response, $value, $group);

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

    protected static function newResultCollection(array $result) 
    {
        $newCollection = [];
        foreach($result as $key => $data) {
            $newCollection[$key]['api_hotel_id'] = $data['id'];
            $newCollection[$key]['name'] = $data['name'];
            $newCollection[$key]['price'] = isset($data['ratePlan']['price']) ? $data['ratePlan']['price']['current'] : '';
            $newCollection[$key]['guest_review_txt'] = isset($data['guestReviews']['badgeText']) ? $data['guestReviews']['badgeText'] : '';
            $newCollection[$key]['guest_review_num'] = isset($data['guestReviews']['rating']) ? $data['guestReviews']['rating'] : '';
            $newCollection[$key]['star_rating'] = $data['starRating'];
            $newCollection[$key]['lat'] = isset($data['coordinate']['lat']) ? $data['coordinate']['lat'] : '';
            $newCollection[$key]['long'] = isset($data['coordinate']['lon']) ? $data['coordinate']['lon'] : '';
            $newCollection[$key]['street_address'] = isset($data['address']['streetAddress']) ? $data['address']['streetAddress'] : '';
            $newCollection[$key]['extended_address'] = isset($data['address']['extendedAddress']) ? $data['address']['extendedAddress'] : '';
            $newCollection[$key]['locality'] = isset($data['address']['locality']) ? $data['address']['locality'] : '';
            $newCollection[$key]['postal_code'] = isset($data['address']['postalCode']) ? $data['address']['postalCode'] : '';
            $newCollection[$key]['region'] = isset($data['address']['region']) ? $data['address']['region'] : '';
            $newCollection[$key]['country_name'] = isset($data['address']['countryName']) ? $data['address']['countryName'] : '';
            $newCollection[$key]['country_code'] = isset($data['address']['countryCode']) ? $data['address']['countryCode'] : '';
            $newCollection[$key]['thumbnail_url'] = isset($data['optimizedThumbUrls']['srpDesktop']) ? $data['optimizedThumbUrls']['srpDesktop'] : '';
        }
        return $newCollection;
    }

    protected static function connectClient($value, $check_in_date = null) 
    {
        $client = 'hotels4';
        $host = env('RAPIDAPI_HOTEL_HOST');
        $key = env('RAPIDAPI_KEY');

        $rapidApiClient = new RapidApiClient($client, $host, $key);
        $response = $rapidApiClient->curlSetup($value, $check_in_date);

        return $response;
    }
}