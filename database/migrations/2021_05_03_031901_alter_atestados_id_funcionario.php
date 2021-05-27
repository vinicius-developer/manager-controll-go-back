<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAtestadosIdFuncionario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('atestados', function (Blueprint $table) {
            $table->foreign('id_funcionario')->references('id_funcionario')->on('funcionarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('atestados', function (Blueprint $table) {
            $table->dropForeign(['id_funcionario']);
        });
    }
}
