<?php

namespace Database\Factories;

use App\Models\Funcionario;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

class FuncionarioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Funcionario::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $responsability = [
            'Assistente Contabil',
            'Contador (a)',
            'Auxiliar Admistrativo',
            'Auxiliar de Serviços Gerais',
            'Programador (a)',
            'Zelador (a)',
            'Pintor (a)',
            'Eletricista (a)',
            'Faxineiro (a)',
            'Recepcionista'
        ];

        $unixTime = time();
        $year = rand(1, 5);
        $days = rand(1,9);
        $mounth = rand(1,5);
        
        return [
            'nome' => $this->faker->name(),
            'cargo' => $responsability[rand(0, 9)],
            'id_empresa' => 1,
            'id_usuario' => 2,
            'admissao' => date(
                'Y-m-d', 
                strtotime(
                    "-$year year -$mounth mounth -$days day", 
                    $unixTime
                )
            )
        ];
    }
}
