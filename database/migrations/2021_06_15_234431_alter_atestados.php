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
            $table->smallInteger('ocorrencia')
                ->default(0);
            $table->smallInteger('tratado')
                ->default(0)
                ->nullable();
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
            $table->dropColumn('ocorrencia');
            $table->dropColumn('tratado');
        });
    }
}
