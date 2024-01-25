<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nro_cliente')->nullable();
            $table->string('nombre',255);
            $table->string('database',255)->nullable();
            $table->string('estado',255)->nullable(); //DEBE SER UN CAMPO BOOLENA??
            $table->unsignedBigInteger('modulo')->nullable();
            $table->boolean('separated')->nullable();
            $table->unsignedBigInteger('separated_reference')->nullable();
            $table->string('nombre_fantasia')->nullable();
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
        Schema::dropIfExists('clientes');
    }
}
