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
        return $this->where('atestados.created_at', '>=', $beginDate . ' 00:00:00')
            ->where('atestados.created_at', '<=', $finalDate . ' 23:59:59')
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
            ->where('f.id_empresa', '=', $client)
            ->selectRaw("
                atestados.id_atestado,
                f.nome as nome,
                atestados.termino_de_descanso - atestados.data_lancamento as dias,
                f.cargo,
                f.setor,
                f.data_de_nascimento,
                STRING_AGG(rac.codigo_cid, ',') as cids
            ")
            ->groupBy(
                'atestados.id_atestado', 
                'f.nome',
                'f.cargo',
                'f.setor',
                'f.data_de_nascimento'
            );
    }


}
