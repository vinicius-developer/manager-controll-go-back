<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_funcionario';

    protected $fillable = [
        'nome',
        'id_empresa',
        'id_usuario'
    ];
}
