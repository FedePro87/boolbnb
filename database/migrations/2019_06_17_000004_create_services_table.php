<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Service;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Service::insert([
            'name' => 'WI-FI',
        ]);
        Service::insert([
            'name' => 'Piscina',
        ]);
        Service::insert([
            'name' => 'Posto Macchina',
        ]);
        Service::insert([
            'name' => 'Portineria',
        ]);
        Service::insert([
            'name' => 'Sauna',
        ]);
        Service::insert([
            'name' => 'Vista mare',
        ]);
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
