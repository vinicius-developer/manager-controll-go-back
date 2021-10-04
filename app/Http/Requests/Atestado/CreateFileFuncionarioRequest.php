<?php

namespace App\Http\Requests\Atestado;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateRuleBeginFinal;

class CreateFileFuncionarioRequest extends FormRequest
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

        if(isset($body->begin_date) && isset($body->final_date)) {
            return [
                'begin_date' => [
                    'required', 
                    new ValidateRuleBeginFinal(
                        $body->begin_date,
                        $body->final_date
                    )
                ],
                'final_date' => ['required']
            ];
        }

        return [
            'begin_date' => ['required'],
            'final_date' => ['required']
        ];
       
    }

    public function messages()
    {
        return [
            'begin_date.required' => 'É necessário inserir a data de inicio',
            'final_date.required' => 'É necessário inserir a data final'
        ];
    }
}
