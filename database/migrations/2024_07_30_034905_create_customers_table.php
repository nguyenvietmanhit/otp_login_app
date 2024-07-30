<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('rank_id')->nullable();
            $table->string('phone')->unique();
//            $table->string('password')->nullable();
            $table->string('fullname')->nullable();
            $table->string('birthday')->nullable();
            $table->tinyInteger('sex')->default(0);
            $table->string('email')->nullable();
            $table->tinyInteger('rank')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

            $table->foreign('rank_id')->references('ns_id')->on('ranks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
