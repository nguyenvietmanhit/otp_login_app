<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id');
            $table->string('fullname');
            $table->string('phone');
            $table->string('alternate_phone')->nullable();
            $table->string('email')->nullable();
            $table->integer('province_id');
            $table->integer('district_id');
            $table->integer('ward_id');
            $table->string('address');
            $table->tinyInteger('is_default')->default(0);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

//            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
//            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
//            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
//            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customer_addresses');
    }
}
