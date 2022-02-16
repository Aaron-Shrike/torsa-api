<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrabajadorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trabajadors', function (Blueprint $table) {
            $table->bigIncrements('codTrabajador');
            $table->string('nombre',50);
            $table->string('apePaterno',50);
            $table->string('apeMaterno',50);
            $table->date('fecNacimiento');
            $table->char('telefono',9);
            $table->string('domicilio',100);
            $table->string('correo',60)->unique();
            $table->unsignedBigInteger('codTipoCargo')->nullable();
            $table->unsignedBigInteger('codConEmergencia')->nullable();

            $table->foreign('codTipoCargo')->references('codTipoCargo')->on('tcargos')->onDelete('set null');

            $table->foreign('codConEmergencia')->references('codConEmergencia')->on('cemergencias')->onDelete('set null');

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
        Schema::dropIfExists('trabajadors');
    }
}
