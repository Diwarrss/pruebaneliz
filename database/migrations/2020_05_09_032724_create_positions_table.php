<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 15);
            $table->timestamps();
        });

        //insertar registros a la tabla
        DB::table('positions')->insert([
            array('id' => '1', 'nombre' => 'Gerente'),
            array('id' => '2', 'nombre' => 'Sub Gerente'),
            array('id' => '3', 'nombre' => 'Administrador'),
            array('id' => '4', 'nombre' => 'Secretaria'),
            array('id' => '5', 'nombre' => 'Operario')
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('positions');
    }
}
