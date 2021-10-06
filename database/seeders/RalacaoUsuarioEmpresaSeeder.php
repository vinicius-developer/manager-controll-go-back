<?php

namespace Database\Seeders;

use App\Models\RelacaoUsuarioEmpresa;
use Illuminate\Database\Seeder;

class RalacaoUsuarioEmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        RelacaoUsuarioEmpresa::create([
            'id_empresa' => 1,
            'id_usuario' => 2
        ]);
        
    }
}
