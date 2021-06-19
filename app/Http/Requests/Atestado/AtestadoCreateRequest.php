<?php

namespace App\Http\Requests\Atestado;

use App\Models\Funcionario;
use Illuminate\Foundation\Http\FormRequest;

class AtestadoCreateRequest extends FormRequest
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
            'funcionario' => ['required', 'exists:funcionarios,id_funcionario'],
            'crm-medico' => ['required', 'max:8'],
            'codigo-cid' => ['required', 'regex:/([0-9A-Z.])/'],
            'data-atestado' => ['required' ,'regex:/(\d{4})-(\d{2})-(\d{2})/'],
            'data-termino' => ['required', 'regex:/(\d{4})-(\d{2})-(\d{2})/']
        ];
    }

    public function messages()
    {
        return [
            'funcionario.required' => 'É necessario informar o funcionario',
            'funcionario.exists' => 'Funcionario não encontrado na  banco de dados',
            'crm-medico.required' => 'É necessario informar o CRM do atestado',
            'crm-medico.max' => 'CRM não contem formato valido',
            'codigo-cid.required' => 'É necessario informar o codigo-cid',
            'codigo-cid.regex' => 'Codigo-cid não contem formato valido',
            'data-atestado.required' => 'É necessario informar a data de emissão do atestado',
            'data-atestado.regex' => 'Data do Atestado não contem formato valido',
            'data-termino.required' => 'É necessario informar a data de retorno ao trabalho',
            'data-termino.regex' => 'Data do Retorno não tem formato valido'
        ];
    }
}
