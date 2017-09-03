<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeasurementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id');
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('body_fat', 10, 2)->nullable();
            $table->decimal('neck', 10, 2)->nullable();
            $table->decimal('shoulders', 10, 2)->nullable();
            $table->decimal('arms', 10, 2)->nullable();
            $table->decimal('chest', 10, 2)->nullable();
            $table->decimal('waist', 10, 2)->nullable();
            $table->decimal('forearms', 10, 2)->nullable();
            $table->decimal('calves', 10, 2)->nullable();
            $table->decimal('thighs', 10, 2)->nullable();
            $table->decimal('hips', 10, 2)->nullable();
            $table->timestamp('date')->nullable();

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
        Schema::dropIfExists('measurements');
    }
}