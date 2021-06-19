<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atestado extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_atestado';

    protected $fillable = [
        'crm_medico',
        'id_funcionario',
        'data_lancamento',
        'termino_de_descanco',
        'id_usuario',
    ];

    public function getAtestadoId($data)
    {

        return $this->where("crm_medico", $data['crm_medico'])
            ->where('id_funcionario', $data['id_funcionario'])
            ->where('data_lancamento', $data['data_lancamento'])
            ->value('id_atestado');

    }

}
