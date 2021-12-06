<?php
/**
 * iceberg - PostCodeInfo.php
 * Initial version by: yilmazoktay124@gmail.com
 * Initial version created on: 3.12.2021
 */

namespace App\Helper;


use Exception;
use Illuminate\Support\Facades\Http;

class PostcodeInfo
{

    const URL = 'https://api.postcodes.io';


    /**
     * @param string $postCode
     * @return object
     * @throws Exception
     */
    public static function postcodeQuery(string $postCode):object
    {
        $postCodeResponse = Http::get(self::URL.'/postcodes/',[
            'q' => $postCode
        ])->object();

        if ($postCodeResponse->status != 200)
            throw new Exception($postCodeResponse->error);
        elseif (empty($postCodeResponse->result))
            throw new Exception('Address not found');

        return $postCodeResponse;
    }

}
