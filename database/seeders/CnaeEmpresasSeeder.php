<?php

namespace Database\Seeders;

use App\Models\CnaeEmpresa;
use Illuminate\Database\Seeder;

class CnaeEmpresasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CnaeEmpresa::create([
            'id_empresa' => 1,
            'codigo_cnae' => '1012-1/02',
        ]);

        CnaeEmpresa::create([
            'id_empresa' => 1,
            'codigo_cnae' => '5231-1/01',
        ]);

    }
}
