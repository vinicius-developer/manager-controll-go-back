<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Usuario::create([
            'nome' => 'Admin',
            'id_tipo_usuario' => 1,
            'email' => 'Admin@admin.com',
            'password' => 'Aa@123456'
        ]);
    }
}
