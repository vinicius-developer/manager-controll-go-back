<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Empresa extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $primaryKey = 'id_empresa';

    protected $fillable = [
        'cnpj',
        'nome_fantasia',
        'razao_social',
    ];

    public function checkEmpreIsActive($id){

        return $this::where('id_empresa', $id)->count();

    }
}
