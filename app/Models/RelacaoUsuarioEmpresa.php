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

    public function getUserEmpre($id){
       return $this->where('id_usuario', $id)->value('id_empresa');
    }

    public function consultUser($id){
        return $this->where('id_empresa', $id)->get('id_usuario');
    }

    public function getReUserEmpre($idUser){
        return $this->where('id_usuario', $idUser)->get();
    }
}
