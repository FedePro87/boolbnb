<?php

use Illuminate\Database\Seeder;
use App\Service;
class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

        // factory(App\Service::class, 6)->create();
    }
}
