<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bureau;

class BureauSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 5; $i++) {
            Bureau::create([
                'bureau_name' => 'Bureau ' . $i,
                'user_id' => 1, // Make sure these users exist
            ]);
        }
    }
}
