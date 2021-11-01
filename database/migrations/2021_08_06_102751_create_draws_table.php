<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('draws', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_begin');
            $table->dateTime('date_end');
            $table->dateTime('date_draw')->index();
            $table->unsignedInteger('amount')->default(1);
            $table->unsignedInteger('spare')->default(0);
            $table->unsignedBigInteger('reward_id')->nullable();
            $table->enum('status', ['wait', 'done', 'empty', 'error'])->index();
            $table->enum('type', ['daily', 'main', 'finish']);
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
        Schema::dropIfExists('draws');
    }
}
