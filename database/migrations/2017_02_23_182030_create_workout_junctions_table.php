<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkoutJunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workout_junctions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('routine_id');
            $table->integer('workout_id');
            $table->string('exercise_name');
            $table->integer('reps');
            $table->integer('set_nr');
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
        Schema::dropIfExists('workout_junctions');
    }
}
