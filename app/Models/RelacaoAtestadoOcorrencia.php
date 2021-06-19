<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelacaoAtestadoOcorrencia extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_relacao_atestado_ocorrencias';

    protected $fillable = [
        'codigo_cid',
        'codigo_cnae',
        'id_atestado'
    ];

}
