<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreIp extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
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
        return 
        [
            'server_id' => 'required',

            'ip' => 'ip',

            'ip_range' => 'required',

            'netmask' => 'required',

            'provider' => 'required',

            'price' => 'numeric',

            'currency' => 'required',

            'type' => 'required',

            'is_active' => 'boolean',
        ];
    }
}
