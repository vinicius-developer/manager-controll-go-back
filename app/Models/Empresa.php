<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'cnpj',
        'nome_fantasia',
        'razao_social',
    ];



}
