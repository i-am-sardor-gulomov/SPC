<?php

namespace App\Http\Requests\v1;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
            'fullname' => 'required|string',
            'phone' => 'string',
            'super_password' => Rule::requiredIf(User::where(['username'=>$this->username])->count()==0 or $this->password),
            'username' => 'required',
            'password' => 'min:6',
            'password_confirm' => 'same:password|required_with:password'
        ];
    }
}
