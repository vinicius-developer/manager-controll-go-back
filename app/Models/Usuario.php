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

    public function isAdmin($id)
    {
        return $this->where('id_usuario', $id)
            ->where('id_tipo_usuario', 1)
            ->fisrt();
    }

}
