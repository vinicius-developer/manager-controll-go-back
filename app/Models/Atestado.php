<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atestado extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_atestado';

    protected $fillable = [
        'crm_medico',
        'id_funcionario',
        'data_lancamento',
        'termino_de_descanso',
        'id_usuario',
        'ocorrencia',
        'tratado'
    ];

    public function getCertificate($id_creatificate)
    {
        return $this->where('id_atestado', $id_creatificate);
    }

    public static function getCertificateStatic($id_creatificate)
    {
        return self::where('id_atestado', $id_creatificate);
    }

}
