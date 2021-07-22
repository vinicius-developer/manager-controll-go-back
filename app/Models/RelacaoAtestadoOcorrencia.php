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

    public function getInfoOcurrence($id_certificate)
    {
        return $this->where('id_atestado', $id_certificate);
    }

}
