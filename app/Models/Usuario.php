<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_usuario';

    protected $fillable = [
        'nome',
        'id_tipo_usuario',
        'email',
        'password',
    ];

    public function getUserWithEmail($email)
    {
        return $this->where('email', $email);
    }

    public function getUserWithId($id)
    {
        return $this->where('id_usuario', $id);
    }

    public function getCompany($id)
    {
        return $this->join(
                'relacao_usuario_empresas as rue',
                'usuarios.id_usuario',
                '=',
                'rue.id_usuario'
            )->join(
                'empresas as e',
                'e.id_empresa',
                '=',
                'rue.id_empresa'
            )->join(
                'cnae_empresas as ce',
                'ce.id_empresa',
                '=',
                'e.id_empresa'
            )
            ->where('usuarios.id_usuario', $id);
    }

    public static function getUserWithIdStatic($id)
    {
        return self::where('id_usuario', $id);
    }

    public static function isAdmin($id)
    {
        return self::where('id_usuario', $id)
            ->where('id_tipo_usuario', 1);
    }

}
