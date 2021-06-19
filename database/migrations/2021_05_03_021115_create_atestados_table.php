<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtestadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atestados', function (Blueprint $table) {
            $table->id('id_atestado');

            $table->char('crm_medico', 13);
            $table->unsignedInteger('id_funcionario');
            $table->unsignedInteger('id_usuario');
            $table->date('data_lancamento');
            $table->date('termino_de_descanso');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atestados');
    }
}
