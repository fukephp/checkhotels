<?php 
namespace App\Custom;

class RapidApiClient 
{
	protected $client = '';

	protected $value = '';

	protected $host = '';

	public function __construct($client, $host, $key) {
		$this->client = $client;
		$this->host = $host;
		$this->key = $key;
	}

	/**
     * Use API from RapidAPI (https://rapidapi.com/apidojo/api/hotels4/)
     * Use API from RapidAPI (https://rapidapi.com/community/api/open-weather-map)
     * @param  [type] $value [description]
     * @return [type]       [description]
     */
	public function curlSetup($value = null) 
    {
        if(is_null($value)) {
            return false;
        }

        $curl_url = '';

        if($this->client == 'hotels4') {
        	$curl_url = "https://hotels4.p.rapidapi.com/locations/v2/search?query={$value}&locale=en_US&currency=USD&limit=3";
        } elseif($this->client == 'weather') {
        	$curl_url = "https://community-open-weather-map.p.rapidapi.com/forecast/daily?q={$value}&cnt=7&units=metric&mode=json";
        }

        $value = rawurlencode($value);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $curl_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                $this->host,
                $this->key
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