<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWinnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('winners', function (Blueprint $table) {
            $table->id();
            $table->date('date_draw')->index();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('reward_in_day_id')->nullable()->default(null);
            $table->timestamps();


            $table->foreign('employee_id')
                ->on('employees')
                ->references('id')
                ->onDelete('CASCADE');
            $table->foreign('reward_in_day_id')
                ->on('reward_in_days')
                ->references('id')
                ->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('winners');
    }
}
