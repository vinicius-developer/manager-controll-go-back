<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterAtestados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('atestados', function(Blueprint $table) {
           $table->unsignedInteger('id_usuario');
           $table->enum('ocorrencia', [1, 0])->default(0);
           $table->enum('tratado', [1]);
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
            $table->dropColumn('id_usuario');
            $table->dropColumn('ocorrencia');
            $table->dropColumn('tratado');
        });
    }
}
