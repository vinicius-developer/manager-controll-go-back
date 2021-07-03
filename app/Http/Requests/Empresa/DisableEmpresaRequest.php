<?php

namespace App\Http\Requests\Empresa;

use Illuminate\Foundation\Http\FormRequest;

class DisableEmpresaRequest extends FormRequest
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
            'code' => ['required', 'regex:/[0-9]/', 'exists:empresas,id_empresa']
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'É necessário informar a empresa',
            'code.regex' => 'Dado informado não é valido',
            'code.exists' => 'Empresa não existe'
        ];
    }
}
