<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CnaeEmpresa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_cnae_empresa';

    protected $fillable = [
        'id_empresa',
        'codigo_cnae'
    ];
}

