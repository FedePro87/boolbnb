<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description');
            $table->float('price')->unsigned();
            $table->integer('number_of_rooms')->unsigned();
            $table->integer('bathrooms')->unsigned();
            $table->integer('bedrooms')->unsigned();
            $table->float('square_meters')->unsigned();
            $table->string('address');
            $table->string('image');
            $table->bigInteger('sponsorship_id')->unsigned()->index();
            $table->bigInteger('user_id')->unsigned()->index();

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
        Schema::dropIfExists('apartments');
    }
}
