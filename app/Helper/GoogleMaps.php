<?php
/**
 * iceberg - GoogleMaps.php
 * Initial version by: yilmazoktay124@gmail.com
 * Initial version created on: 3.12.2021
 */

namespace App\Helper;


use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;

class GoogleMaps
{

    const DISTANCE_MATRIX_URL = 'https://maps.googleapis.com/maps/api/distancematrix/json';
    private static $apiKey = 'AIzaSyAn6xul76CRxVdC4xWzzKzWGj3u-l6C6gw';
    private static $language = 'en-En';

    /**
     * @param string $origins
     * @param string $destinations
     * @param string $mode driving|walking|bicycling|transit
     * @param string $units imperial|metric
     * @return object
     * @throws Exception
     */
    static public function distanceMatrix(string $origins, string $destinations, string $mode = 'driving',/* Date|string $departure_time = 'now',*/ string $units = 'metric'): object
    {

        $query = [
            'origins' => $origins,
            'destinations' => $destinations,
            'mode' => $mode,
            'key' => self::$apiKey,
            'language' => self::$language,
            'units' => $units
        ];
       /* if (($mode == 'driving' || $mode == 'transit')) {
            $query = array_merge($query, ['departure_time' => $departure_time]);
        }*/


        $googleResponse = Http::get(self::DISTANCE_MATRIX_URL, $query)->object();

        if ($googleResponse->status != 'OK')
            throw new Exception('Google Maps Error');

        if ($googleResponse->rows[0]->elements[0]->status == 'NOT_FOUND')
            throw new Exception('Not Found Address');

        return $googleResponse;
    }

}
