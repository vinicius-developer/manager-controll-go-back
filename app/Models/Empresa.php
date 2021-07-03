<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

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

    public static function checkEmpreIsActiveStatic($id)
    {
        return self::where('id_empresa', $id);
    }

    public function getCompanyWithCnpj($cnpj)
    {
        return $this->where('cnpj', $cnpj);
    }

    /**
     * Pega um empresa com o id dela 
     * 
     * @param int $id
     */
    public function getCompanyWithId($id)
    {
        return $this->where('id_empresa', $id);
    }
}
