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

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->integer('routine_id')->unsigned();
            $table->foreign('routine_id')
                ->references('id')->on('routines')
                ->onDelete('cascade');

            $table->integer('workout_id')->unsigned();
            $table->foreign('workout_id')
                ->references('id')->on('workouts')
                ->onDelete('cascade');

            $table->string('exercise_name');
            $table->boolean('is_warmup')->default(0);
            $table->integer('set_nr');
            $table->integer('reps');
            $table->string('weightType')->default('weight');
            $table->string('weight');
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
