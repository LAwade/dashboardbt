<?php

namespace Database\Seeders;

use App\Models\Court;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $courts = [
            ['name' => 'Quadra 1', 'number' => 1],
            ['name' => 'Quadra 2', 'number' => 2],
            ['name' => 'Quadra 3', 'number' => 3],
            ['name' => 'Quadra 4', 'number' => 4],
            ['name' => 'Quadra 5', 'number' => 5],
            ['name' => 'Quadra 6', 'number' => 6],
            ['name' => 'Quadra 7', 'number' => 7],
            ['name' => 'Quadra 8', 'number' => 8],
            ['name' => 'Quadra 9', 'number' => 9],
            ['name' => 'Quadra 10', 'number' => 10],
        ];

        foreach ($courts as $court) {
            Court::create([
                'id' => Str::uuid(),
                'name' => $court['name'],
                'number' => $court['number']
            ]);
        }
    }
}
