<?php

namespace App\Http\Requests\Usuario;

use Illuminate\Foundation\Http\FormRequest;

class CreateUsuarioRequest extends FormRequest
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
            'nome' => ['required', 'max:100'],
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['required', 'confirmed'],
            'empresa' => ['exists:empresas,id_empresa', 'required'],
            'telefones_usuarios.*' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'É necessário informar o campo nome',
            'nome.max' => 'Este nome é muito longo',
            'email.email' => 'E-mail não contem formato valido',
            'password.required' => 'É necessário informar uma senha',
            'password.confirmed' => 'As senha não são iguais',
            'empresa.exists' => 'Essa empresa não está cadastrada em nosso sistema',
            'telefones_usuarios.*.required' => 'É necessário informar o telefone',
        ];
    }
}
