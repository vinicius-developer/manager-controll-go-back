<?php

namespace Database\Seeders;

use App\Models\Empresa;
use Illuminate\Database\Seeder;

class EmpresasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Empresa::create([
            'cnpj' => '50542074000128',
            'nome_fantasia' => 'ForTest Corp',
            'razao_social' => 'Your Test Ltda',
        ]);

    }
}
