<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\Bureau;

class SectionSeeder extends Seeder
{
    public function run()
    {
        $bureaus = Bureau::all();

        foreach ($bureaus as $bureau) {
            for ($i = 1; $i <= 3; $i++) {
                Section::create([
                    'section_name' => $bureau->bureau_name . ' - Section ' . $i,
                    'bureau_id' => $bureau->id,
                    'user_id' => $bureau->user_id, 
                ]);
            }
        }
    }
}
