<?php

namespace App\Http\Requests\Atestado;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateRuleBeginFinal;

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
        $body = json_decode($this->getContent());

        $rules = ['required', 'regex:/(\d{4})-(\d{2})-(\d{2})/'];

        if(isset($body->data_atestado) && isset($body->data_termino)) {
            $rules[] = new ValidateRuleBeginFinal(
                $body->data_atestado,
                $body->data_termino
            );
        }

        return [
            'funcionario' => ['required', 'exists:funcionarios,id_funcionario'],
            'crm_medico' => ['required', 'max:15'],
            'codigo_cid.*' => ['required', 'regex:/([0-9A-Z.])/'],
            'data_atestado' => $rules,
            'data_termino' => ['required', 'regex:/(\d{4})-(\d{2})-(\d{2})/']
        ];
    }

    public function messages()
    {
        return [
            'funcionario.required' => 'É necessario informar o funcionario',
            'funcionario.exists' => 'Funcionario não encontrado na  banco de dados',
            'crm_medico.required' => 'É necessario informar o CRM do atestado',
            'crm_medico.max' => 'CRM não contem formato valido',
            'codigo-cid.*.required' => 'É necessario informar o codigo-cid',
            'codigo-cid.*.regex' => 'Codigo-cid não contem formato valido',
            'data_atestado.required' => 'É necessario informar a data de emissão do atestado',
            'data_atestado.regex' => 'Data do Atestado não contem formato valido',
            'data_termino.required' => 'É necessario informar a data de retorno ao trabalho',
            'data_termino.regex' => 'Data do Retorno não tem formato valido'
        ];
    }
}
