<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelacaoUsuarioEmpresa extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_relacao_usuario_empresas';

    protected $fillable = [
        'id_empresa',
        'id_usuario'
    ];

    public function getUserEmpre($id_user)
    {
       return $this->where('id_usuario', $id_user)->value('id_empresa');
    }

    public function getUsersCompanies($id_company)
    {
        return $this->where('id_empresa', $id_company)
            ->join('usuarios as u', 'u.id_usuario', '=', 'relacao_usuario_empresas.id_usuario');
    }

    public function getReUserEmpre($id_user)
    {
        return $this->where('id_usuario', $id_user)->get();
    }

    public function getRelationship($id_user, $id_company)
    {
        return $this->where('id_usuario', $id_user)
            ->where('id_empresa', $id_company);
    }

    public static function getRelationshipStatic($id_user, $id_company)
    {
        return self::where('id_usuario', $id_user)
            ->where('id_empresa', $id_company);
    }

    public function getCompanies($id_user) 
    {
        return $this->where('id_usuario', $id_user)
            ->join('empresas AS e', 'e.id_empresa', '=', 'relacao_usuario_empresas.id_empresa');
    }

}
