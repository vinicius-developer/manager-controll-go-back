<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCnaeEmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cnae_empresas', function (Blueprint $table) {
            $table->id('id_cnae_empresa');

            $table->unsignedInteger('id_empresa');
            $table->char('codigo_cnae', 9);

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
        Schema::dropIfExists('cnae_empresas');
    }
}
