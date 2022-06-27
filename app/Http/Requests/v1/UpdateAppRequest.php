<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (auth()->check() and $this->user()->tokenCan('admin'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'description' => 'string',
            'url' => 'required|string',
            'IP' => 'required|ip',
            'port' => 'required|numeric',
            'icon' => 'string',
            'grant_type' => 'required|string',
            'client_id' => 'required|numeric',
            'client_secret' => 'required|string',
        ];
    }
}
