<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelacaoUsuarioEmpresa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_relacao_usuario_empresa';

    protected $fillable = [
        'id_empresa',
        'id_usuario'
    ];
}
