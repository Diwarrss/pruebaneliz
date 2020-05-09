<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employes', function (Blueprint $table) {
            $table->id();
            $table->string('documento', 15);
            $table->string('nombres');
            $table->string('apellidos');
            $table->date('fecha_nacimiento');
            $table->enum('sexo', ['H', 'M']);
            $table->string('foto');
            $table->boolean('estado_contrato');
            //foreing keys
            $table->unsignedBigInteger('civilstate_id');
            $table->foreign('civilstate_id')->references('id')->on('civil_states');
            $table->unsignedBigInteger('position_id');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->unsignedBigInteger('immediateboss_id')->nullable();
            $table->foreign('immediateboss_id')->references('id')->on('employes');
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
        Schema::dropIfExists('employes');
    }
}
