<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCredentialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'front_login'=>'string',
            'front_password'=>'string',
            'front_password_old'=>'string|required_with:front_password',
            'back_login'=>'string',
            'back_password'=>'string',
            'back_password_old'=>'string|required_with:back_password'
        ];
    }
}
