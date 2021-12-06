<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentFilterRequest extends FormRequest
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
            'start_datetime' => 'nullable|date_format:Y-m-d H:i',
            'end_datetime' => 'nullable|date_format:Y-m-d H:i',
        ];
    }
}
