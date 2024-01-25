<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('nro_cliente')->nullable();
            $table->unsignedBigInteger('id_servicio')->nullable();
            $table->string('periodo', 255)->index();
            $table->unsignedBigInteger('cantidad_activos')->nullable();
            $table->unsignedBigInteger('cantidad_inactivos')->nullable(); 
            $table->unsignedBigInteger('remito')->nullable(); 
            $table->unsignedBigInteger('modulo')->nullable(); 
            $table->string('nombre_fantasia')->nullable();
            

 

            $table->timestamps();

            // $table->foreign('id_cliente')->references('id')->on('clientes')->on('no action');
            $table->foreign('id_servicio')->references('id')->on('servicios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facturacions');
    }
}
