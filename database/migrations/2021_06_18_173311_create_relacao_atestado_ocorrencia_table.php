<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelacaoAtestadoOcorrenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relacao_atestado_ocorrencias', function (Blueprint $table) {
            $table->id('id_relacao_atestado_ocorrencias');

            $table->string('codigo_cid', 5);
            $table->string('codigo_cnae', 9);
            $table->unsignedBigInteger('id_atestado');

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
        Schema::dropIfExists('relacao_atestado_ocorrencias');
    }
}
