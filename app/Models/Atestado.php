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

    public function getCertificate($id_certificate)
    {
        return $this->where('id_atestado', $id_certificate);
    }

    public static function getCertificateStatic($id_certificate)
    {
        return self::where('id_atestado', $id_certificate);
    }

    public function getReportFromDates($beginDate, $finalDate, $client)
    {
        return $this->where('atestados.created_at', '>=', $beginDate)
            ->where('atestados.created_at', '<=', $finalDate)
            ->join('funcionarios as f',
                'f.id_funcionario',
                '=',
                'atestados.id_funcionario'
            )
            ->join('relacao_atestado_cids as rac',
                'rac.id_atestado',
                '=',
                'atestados.id_atestado',
            )
            ->where('f.id_empresa', '=', $client);
            
    }


}
