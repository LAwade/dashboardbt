<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the statuses to be seeded
        $statuses = [
            ['name' => 'Administrador'],
            ['name' => 'Juiz Geral'],
            ['name' => 'Juiz Quadra'],
            ['name' => 'Jogador']
        ];

        // Insert the statuses into the database
        foreach ($statuses as $status) {
            Permission::create([
                'name' => $status['name'],
            ]);
        }
    }
}
