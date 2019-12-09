<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrar extends FormRequest
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
            'name' => 'required|string',

            'company' => 'required|string',

            'eml_contact' => '',

            'password' => 'required',

            'customerid' => 'required',

            'registrar_key' => '',

            'secret' => '',

            'website' => 'required|url',

            'phone' => '',

            'address' => '',

            'is_active' => 'boolean',

            'description' => '',

        ];
    }
}
