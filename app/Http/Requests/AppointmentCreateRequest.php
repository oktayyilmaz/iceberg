<?php

namespace App\Http\Requests;


use App\Rules\PostCodeCountry;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentCreateRequest extends FormRequest
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
            'datetime' => 'required|date_format:Y-m-d H:i',
            'address' => [
                'required', 'max:50', 'min:5', new PostCodeCountry('England')
            ],
        ];
    }
}
