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
            'type' => '24 ore',
        ]);
        Sponsorship::insert([
            'type' => '72 ore',
        ]);
        Sponsorship::insert([
            'type' => '144 ore',
        ]);
    }
}


