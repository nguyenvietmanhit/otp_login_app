<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodeLoyaltyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('code_loyalty', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('phone');
            $table->string('code');
            $table->string('description')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamp('end_date');
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
        Schema::dropIfExists('code_loyalty');
    }
}
