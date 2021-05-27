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
            'tipo_usuario' => ['required', 'exists:tipo_usuarios,id_tipo_usuario'],
            'email' => ['required', 'email:rfc,dns'],
            'password' => ['required', 'confirmed'],
            'empresa' => ['required_if:tipo_usuario,==,2', 'exists:empresas,id'],
            'telefones_usuarios.*' => ['required', 'regex:/(\(?\d{2}\)?\s)?(\d{4,5}\-\d{4})/']
        ];
    }

    public function messages()
    {
        return [
            'nome.required' => 'É necessário informar o campo nome',
            'nome.max' => 'Este nome é muito longo',
            'tipo_usuario.required' => 'É necessário informar o tipo de usuário',
            'tipo_usuario.exists' => 'Este tipo de usuário não existe',
            'email.required' => 'É necessário informar o e-mail',
            'email.email' => 'E-mail não contem formato valido',
            'password.required' => 'É necessário informar uma senha',
            'password.confirmed' => 'As senha não são iguais',
            'empresa.required_if' => 'É necessário informar a empresa',
            'empresa.exists' => 'Essa empresa não está cadastrado em nosso sistema',
            'telefones_usuarios.*.required' => 'É necessário informar o telefone',
            'telefones_usuarios.*.regex' => 'Telefone não possui formato válido'
        ];
    }
}
