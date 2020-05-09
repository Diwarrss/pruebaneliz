<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCivilStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('civil_states', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 15);
            $table->timestamps();
        });

        //insertar registros a la tabla
        DB::table('civil_states')->insert([
            array('id' => '1', 'nombre' => 'Soltero(a)'),
            array('id' => '2', 'nombre' => 'Unión libre'),
            array('id' => '3', 'nombre' => 'Casado(a)'),
            array('id' => '4', 'nombre' => 'Divorsiado(a)'),
            array('id' => '5', 'nombre' => 'Viudo(a)')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('civil_states');
    }
}
