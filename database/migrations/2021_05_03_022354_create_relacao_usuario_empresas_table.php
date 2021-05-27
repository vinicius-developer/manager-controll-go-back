<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRelacaoUsuarioEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('relacao_usuario_empresas', function (Blueprint $table) {
            $table->id('id_relacao_usuario_empresas');

            $table->unsignedInteger('id_usuario');
            $table->unsignedInteger('id_empresa');

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
        Schema::dropIfExists('relacao_usuario_empresas');
    }
}
