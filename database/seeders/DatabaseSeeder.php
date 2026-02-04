<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Website;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create single client
        $client = Client::firstOrCreate(['email' => 'sriramdev.tech@gmail.com']);
    }
}
