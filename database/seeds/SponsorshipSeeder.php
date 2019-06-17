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
        factory(Sponsorship::class, 3)->create();
    }
}
