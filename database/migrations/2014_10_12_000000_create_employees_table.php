<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->unique();
            $table->string('registration_code')->unique()->nullable();
            $table->enum('type', ['terrain', 'stationary'])->nullable()->default('stationary');
            $table->integer('years_of_employment')->unsigned()->nullable();
            $table->date('end_of_work')->nullable();
            $table->string('password')->nullable();
            $table->boolean('agreement_1')->default(0);
            $table->text('agreement_1_text')->nullable();
            $table->boolean('agreement_2')->default(0);
            $table->text('agreement_2_text')->nullable();
            $table->boolean('active')->default(true);
            $table->rememberToken();
            $table->string('registered_at')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
