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
            $table->foreign('id_funcionarios')->references('id_funcionarios')->on('funcionarios');
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
            $table->dropForeign(['id_funcionarios']);
        });
    }
}
