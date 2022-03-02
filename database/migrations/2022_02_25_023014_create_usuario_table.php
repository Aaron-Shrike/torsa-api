<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->increments('codUsuario');
            $table->char('dni', 8)->unique();
            $table->string('contrasenia', 60);
            $table->boolean('activo');
            $table->unsignedInteger('codTrabajador')->unique()->nullable();
            $table->unsignedInteger('codTipoUsuario')->nullable();

            $table->foreign('codTrabajador')
            ->references('codTrabajador')
            ->on('trabajador');

            $table->foreign('codTipoUsuario')
            ->references('codTipoUsuario')
            ->on('tipousuario');

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
        Schema::dropIfExists('usuario');
    }
}
