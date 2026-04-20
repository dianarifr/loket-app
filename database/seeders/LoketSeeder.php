<?php

namespace Database\Seeders;

use App\Models\Layanan;
use App\Models\Loket;
use Illuminate\Database\Seeder;

class LoketSeeder extends Seeder
{
    public function run(): void
    {
        $cs = Layanan::create(['nama_layanan' => 'Customer Service', 'prefix' => 'A']);
        $teller = Layanan::create(['nama_layanan' => 'Teller', 'prefix' => 'B']);
        $admin = Layanan::create(['nama_layanan' => 'Admin', 'prefix' => 'C']);

        $loket1 = Loket::create(['nama_loket' => 'Loket 1']);
        $loket2 = Loket::create(['nama_loket' => 'Loket 2']);

        // Loket 1 melayani semua layanan
        $loket1->layanans()->attach([$cs->id, $teller->id, $admin->id]);

        // Loket 2 hanya melayani Customer Service
        $loket2->layanans()->attach([$cs->id]);
    }
}
