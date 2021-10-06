<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Funcionario extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'funcionarios';

    protected $primaryKey = 'id_funcionario';

    protected $fillable = [
        'nome',
        'cargo',
        'id_empresa',
        'id_usuario',
        'admissao',
        'data_de_nascimento',
        'setor'
    ];

    public function getFuncId($id_funcionario, $id_empresa)
    {
        return $this->where('id_empresa', $id_empresa)
            ->where('id_funcionario', $id_funcionario);
    }

    public function getEmployeeWithId($id)
    {
        return $this->where('id_funcionario', $id);
    }

    public static function checkIfCompanyHasAccessEmployeeStatic($id_company, $id_employee)
    {
        return self::where('id_funcionario', $id_employee)
            ->where('id_empresa', $id_company);
    }

    public function getAllEmployeeCompanies($id_company)
    {
        return $this->where('id_empresa', $id_company);
    }

    public function getAllCertificateWithYear($id_company, $year)
    {
        return $this->where('funcionarios.id_empresa', $id_company)
            ->where('a.created_at', 'like', "%$year%")
            ->whereNull('funcionarios.deleted_at')
            ->join('atestados AS a', 
                'a.id_funcionario', 
                '=', 
                'funcionarios.id_funcionario'
            );
    }

    public function getAllCertificateYearAndEmployee($id_company, $year, $id_employee)
    {
        return $this->where('funcionarios.id_empresa', $id_company)
            ->where('a.created_at', 'like', "%$year%")
            ->where('a.id_funcionario', $id_employee)
            ->whereNull('funcionarios.deleted_at')
            ->join('atestados AS a', 
                'a.id_funcionario', 
                '=', 
                'funcionarios.id_funcionario'
            );
    }

    public function getUntreatedCertificates($id_company)
    {
        return $this->where('id_empresa', $id_company)
            ->where('ocorrencia', '<>', 0)
            ->where('tratado', 0)
            ->whereNull('funcionarios.deleted_at')
            ->join('atestados AS a', 
                'a.id_funcionario', 
                '=', 
                'funcionarios.id_funcionario'
            );
    }


}
