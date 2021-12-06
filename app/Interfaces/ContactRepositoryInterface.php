<?php
/**
 * iceberg - ContactRepositoryInterface.php
 * Initial version by: yilmazoktay124@gmail.com
 * Initial version created on: 3.12.2021
 */

namespace App\Interfaces;


interface ContactRepositoryInterface
{
    public function firstOrCreateContact(array $contactWhere,array $contactDetail);
    public function updateContact(int $contactId,array $contactDetail);

}
