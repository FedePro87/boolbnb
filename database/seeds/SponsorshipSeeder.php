<?php

use Illuminate\Database\Seeder;
use App\Apartment;
use App\User;
use App\Service;
use App\Message;
use App\Sponsorship;

class SponsorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            
        Sponsorship::insert([
            'type' => 1440,
        ]);
        Sponsorship::insert([
            'type' => 4320,
        ]);
        Sponsorship::insert([
            'type' => 8640,
        ]);
    }
}


