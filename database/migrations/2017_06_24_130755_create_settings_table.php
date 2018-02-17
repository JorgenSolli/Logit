<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
                
            $table->string('timezone')->default('UTC');
            $table->string('unit')->default('Metric');
            $table->boolean('recap')->default(1);
            $table->boolean('share_workouts')->default(0);
            $table->boolean('accept_friends')->default(0);
            $table->boolean('strict_previous_exercise')->default(1);
            $table->boolean('count_warmup_in_stats')->default(0);
            $table->boolean('use_timer')->default(0);
            $table->boolean('timer_play_sound')->default(0);
            $table->string('timer_direction')->default('default');
            $table->integer('timer_seconds')->default(0);
            $table->integer('timer_minutes')->default(0);
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
        Schema::dropIfExists('settings');
    }
}
