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

    public function getCompanyCnaes($id)
    {
        return $this->where('id_empresa', $id);
    }

    public function getWithIds(array $ids)
    {
        return $this->wherein('id_cnae_empresa', $ids);
    }
}

