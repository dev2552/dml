<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProvider extends FormRequest
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
            'url_site' => 'required|url',

            'name' => 'required',

            'country' => 'required',

            'cpanel' => 'required',

            'login' => 'required',

            'pwd' => 'required',

            'id_customer' => 'required',

            'inscrfname' => 'required',

            'inscrlname' => 'required',

            'status' => 'required',

            'type' => 'required',

            'note' => 'required',

            'inscr_email' => 'required|email',
        ];
    }
}
