<?php

namespace App\Http\Requests;

use App\Rules\PostcodeCountry;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @todo Check user control
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'datetime' => 'date_format:Y-m-d H:i',
            'address' => [
                'max:50', 'min:5', new PostcodeCountry('England')
            ],
        ];
    }
}
