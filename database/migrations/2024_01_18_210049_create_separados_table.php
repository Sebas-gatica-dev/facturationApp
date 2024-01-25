<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeparadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('separados', function (Blueprint $table) {
            $table->id();
            $table->string('empresa');
            $table->unsignedBigInteger('nro_cliente_separated_from_clientup');
            $table->unsignedBigInteger('nro_separado');
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
        Schema::dropIfExists('separados');
    }
}
