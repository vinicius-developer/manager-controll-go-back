<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelacaoAtestadoCid extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_relacao_atestado_cids';

    protected $fillable = [
        'codigo_cid',
        'id_atestado',
    ];

    public function getRegister($id_atestado) {
        return $this->where('id_atestado', $id_atestado);
    }
}

