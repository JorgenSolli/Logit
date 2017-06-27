<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutineJunctionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routine_junctions', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->integer('routine_id')->unsigned();
            $table->foreign('routine_id')
                ->references('id')->on('routines')
                ->onDelete('cascade');

            $table->boolean('is_warmup')->defeault(0);
            $table->string('exercise_name');
            $table->string('muscle_group');
            $table->integer('goal_reps');
            $table->integer('goal_sets');
            $table->integer('goal_weight');
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
        Schema::dropIfExists('routine_junctions');
    }
}
