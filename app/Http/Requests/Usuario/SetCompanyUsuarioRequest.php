<?php

namespace App\Http\Requests\Usuario;

use Illuminate\Foundation\Http\FormRequest;

class SetCompanyUsuarioRequest extends FormRequest
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
            'company' => ['required', 'regex:/[0-9]/', 'exists:empresas,id_empresa']
        ];
    }

    public function messages()
    {
        return [
            'company.required' => 'É necessário informar o campo company',
            'company.regex' => 'Dado informado não é valido',
            'company.exists' => 'Empresa não existe'
        ];
    }
}
