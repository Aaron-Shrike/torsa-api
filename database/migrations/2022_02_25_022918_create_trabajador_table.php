<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrabajadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trabajador', function (Blueprint $table) {
            $table->increments('codTrabajador');
            $table->string('nombre',50);
            $table->string('apePaterno',50);
            $table->string('apeMaterno',50);
            $table->date('fecNacimiento');
            $table->char('telefono',9);
            $table->string('domicilio',100);
            $table->string('correo',60)->unique();
            $table->unsignedInteger('codTipoCargo');
            $table->unsignedInteger('codConEmergencia');

            $table->foreign('codTipoCargo')
            ->references('codTipoCargo')
            ->on('tipocargo');

            $table->foreign('codConEmergencia')
            ->references('codConEmergencia')
            ->on('contactoemergencia');
            
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
        Schema::dropIfExists('trabajador');
    }
}
