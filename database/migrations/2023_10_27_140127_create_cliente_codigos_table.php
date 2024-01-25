<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteCodigosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cliente_codigos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_servicio');
            $table->unsignedBigInteger('codigo'); // NOSE SI EL CODIGO ES ALFANUMERICO


            $table->foreign('id_cliente')->references('id')->on('clientes')->nullable();
            $table->foreign('id_servicio')->references('id')->on('servicios');
            $table->foreign('codigo')->references('codigo')->on('codigos');
          
            $table->primary(['id_cliente','id_servicio']); //codigo DEBE FORMAR PARTE DE LA CLAVE PRIMARIA??

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
        Schema::dropIfExists('cliente_codigos');
    }
}
