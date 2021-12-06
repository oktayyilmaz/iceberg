<?php
/**
 * iceberg - ContactRepository.php
 * Initial version by: yilmazoktay124@gmail.com
 * Initial version created on: 3.12.2021
 */

namespace App\Repositories;

use App\Interfaces\ContactRepositoryInterface;
use App\Models\Contact;

class ContactRepository implements ContactRepositoryInterface
{

    public function firstOrCreateContact(array $contactWhere, array $contactDetail)
    {
        return Contact::firstOrCreate($contactWhere, $contactDetail);
    }

    public function updateContact(int $contactId, array $contactDetail)
    {
        return Contact::whereId($contactId)->update($contactDetail);
    }
}
