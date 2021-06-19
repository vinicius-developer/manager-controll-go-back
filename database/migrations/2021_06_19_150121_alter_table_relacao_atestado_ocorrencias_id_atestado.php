<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableRelacaoAtestadoOcorrenciasIdAtestado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('relacao_atestado_ocorrencias', function (Blueprint $table) {
            $table->foreign('id_atestado')->references('id_atestado')->on('atestados');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('relacao_atestado_ocorrencias', function (Blueprint $table) {
            $table->dropForeign(['id_atestado']);
        });

    }
}
