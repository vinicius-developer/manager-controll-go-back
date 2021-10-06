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
        $responsabilityAndSector = [ 
            [
                'Assistente Contabil',
                "Administrativo"
            ],
            [
                'Contador (a)',
                "Administrativo"
            ],
            [
                'Auxiliar Admistrativo',
                "Administrativo"
            ],
            [
                'Recepcionista',
                'Administrativo'
            ],
            [
                'Auxiliar de Serviços Gerais',
                'Conservação e Limpeza'
            ],
            [
                'Programador (a)',
                'TI'
            ],
            [
                'Zelador (a)',
                'Serviços Gerais'
            ],
            [
                'Pintor (a)',
                'Manutenção Geral'
            ],
            [
                'Eletricista (a)',
                'Manutenção Geral'
            ],
            [
                'Faxineiro (a)',
                'Conservação e Limpeza'
            ],
        ];

        $responsabilityAndSectorNumber = rand(0, 9);


        return [
            'nome' => $this->faker->name(),
            'cargo' => $responsabilityAndSector[$responsabilityAndSectorNumber][0],
            'id_empresa' => 1,
            'id_usuario' => 2,
            'admissao' => $this->faker->dateTimeBetween('-5 year', '-1 year'),
            'data_de_nascimento' => $this->faker->dateTimeBetween('-40 year', '-18 year'),
            'setor' => $responsabilityAndSector[$responsabilityAndSectorNumber][1],
        ];
    }
}
