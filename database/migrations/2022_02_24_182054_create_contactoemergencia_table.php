<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactoemergenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contactoemergencia', function (Blueprint $table) {
            $table->bigIncrements('codConEmergencia');
            $table->string('nombre',50);
            $table->char('numero',9)->unique();
            $table->string('parentesco',20);
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
        Schema::dropIfExists('contactoemergencia');
    }
}
