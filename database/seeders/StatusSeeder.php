<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the statuses to be seeded
        $statuses = [
            ['name' => 'AGUARDANDO'],
            ['name' => 'EM ANDAMENTO'],
            ['name' => 'CONFIRMADO'],
            ['name' => 'CANCELADO'],
            ['name' => 'FINALIZADO'],
        ];

        // Insert the statuses into the database
        foreach ($statuses as $status) {
            Status::create([
                'name' => $status['name'],
            ]);
        }
    }
}
