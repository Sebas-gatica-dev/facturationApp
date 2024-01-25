<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicioCodigosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicio_codigos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_servicio');
            $table->unsignedBigInteger('codigo');
            $table->timestamps();


            $table->foreign('id_servicio')->references('id')->on('servicios')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('codigo')->references('codigo')->on('codigos')->cascadeOnUpdate()->cascadeOnDelete(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicio_codigos');
    }
}
