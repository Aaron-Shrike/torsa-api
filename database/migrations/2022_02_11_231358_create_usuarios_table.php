<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('codUsuario');
            $table->char('dni', 8)->unique();
            $table->string('contrasenia', 30);
            $table->boolean('activo');
            $table->unsignedBigInteger('codTrabajador')->unique()->nullable();
            $table->unsignedBigInteger('codTipoUsuario')->nullable();

            $table->foreign('codTrabajador')
            ->references('codTrabajador')
            ->on('trabajadors')
            ->onDelete('set null');

            $table->foreign('codTipoUsuario')
            ->references('codTipoUsuario')->on('tusuarios')
            ->onDelete('set null');

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
        Schema::dropIfExists('usuarios');
    }
}
