<?php

namespace Database\Seeders;

use App\Models\Funcionario;
use Carbon\Factory;
use Database\Factories\EmployeeFactory;
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

        $this->call([
            TipoUsuarioSeeder::class,
            UsuarioSeeder::class,
            EmpresasSeeder::class,
            RalacaoUsuarioEmpresaSeeder::class,
        ]);


        Funcionario::factory()->count(120)->create();
    }
}
