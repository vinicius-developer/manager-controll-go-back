<?php

namespace App\Http\Requests\Usuario;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticateUsuarioRequest extends FormRequest
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
        return [
            'email' => ['required', 'exists:usuarios,email'],
            'password' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'É necessário informar o e-mail',
            'email.exists' => 'Esse e-mail não está cadastrado em nosso sistema',
            'password.required' => 'É necessário informar a senha'
        ];
    }
}
