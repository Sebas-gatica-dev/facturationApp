<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        
        Schema::create('servicios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('servicio',255);
            $table->string('descripcion',255);
            $table->timestamps();

            //DEBE SEÑALAR LA LONGITUD ESPECIFICA DE LOS CAMPOS servicio Y descripcion. 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicios');
    }
}
