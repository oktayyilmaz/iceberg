<?php

namespace App\Rules;

use App\Helper\PostcodeInfo;
use Exception;
use Illuminate\Contracts\Validation\Rule;

class PostCodeCountry implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $country;

    public function __construct($country)
    {
        $this->country = $country;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws Exception
     */
    public function passes($attribute, $value)
    {

        try {
           return PostcodeInfo::postcodeQuery($value)->result[0]->country == $this->country;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You can only enter a ' . $this->country . ' :attribute.';
    }
}
