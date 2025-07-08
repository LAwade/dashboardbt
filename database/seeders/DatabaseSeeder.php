<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'id' => Str::uuid(),
            'name' => 'Lucas Awade',
            'email' => 'lucasawade46@gmail.com',
            'password' => bcrypt('12345678'), // Senha padrão para o usuário
            'permission_id' => 1, // ID do permissionário de administrador
        ]);
    }
}
