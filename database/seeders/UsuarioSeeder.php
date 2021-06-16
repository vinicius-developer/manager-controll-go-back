<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Traits\Authenticate;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    use Authenticate;

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
            'password' => $this->generatePassword('Aa@123456')
        ]);
    }
}
