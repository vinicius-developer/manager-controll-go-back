<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TipoUsuarioSeeder::class);
        $this->call(UsuarioSeeder::class);
        $this->call(EmpresasSeeder::class);
        $this->calL(RalacaoUsuarioEmpresaSeeder::class);
    }
}
