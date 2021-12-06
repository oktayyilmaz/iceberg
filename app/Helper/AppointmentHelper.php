<?php
/**
 * iceberg - AppointmentHelper.php
 * Initial version by: yilmazoktay124@gmail.com
 * Initial version created on: 4.12.2021
 */

namespace App\Helper;


use Carbon\Carbon;
use DateTime;
use Exception;

class AppointmentHelper
{

    /**
     * @param string $address
     * @param DateTime $dateTime
     * @return object
     * @throws Exception
     */

    public static function distanceCalculate(string $address, Carbon $appointmentDatetime): object
    {
        $mainAddress = config('default.mainAddress');
        $appointmentTime = config('default.appointmentTime');

        $googleMaps = GoogleMaps::distanceMatrix($mainAddress, $address);
        $googleMapElements = $googleMaps->rows[0]->elements[0];

        $distance = $googleMapElements->distance->value;

        $officeCheckInAt = (clone $appointmentDatetime)->addSeconds($appointmentTime + $googleMapElements->duration->value);
        $officeCheckOutAt = (clone $appointmentDatetime)->addSeconds(-1 * $googleMapElements->duration->value);

        return (object)[
            'distance' => $distance,
            'appointmentDatetime' => $appointmentDatetime,
            'officeCheckInAt' => $officeCheckInAt,
            'officeCheckOutAt' => $officeCheckOutAt,
        ];
    }
}
