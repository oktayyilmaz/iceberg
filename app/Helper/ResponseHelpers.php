<?php
/**
 * iceberg - ResponseHelpers.php
 * Initial version by: yilmazoktay124@gmail.com
 * Initial version created on: 3.12.2021
 */

namespace App\Helper;


trait ResponseHelpers
{

    /**
     * @param array $data
     * @param int $code
     */
    public function response(array|object $data, int $code = 200)
    {
        return response()->json($data, $code);
    }


}
