<?php

namespace Database\Seeders;

use App\Models\Church;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Church::create([
            'name'=>'JKUSDA',
            'address'=>'62000 - 00200 Nairobi',
            'phone'=>'0701583807',
            'email'=>'jkusda2019@gmail.com',
            'website'=>'www.jkusdachurch.org',
            'location'=> '-1.0941666780987673, 37.01447530792765',
        ]);
    }
}
