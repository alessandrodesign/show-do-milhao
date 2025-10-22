<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ⚡ 1. Criação de permissões básicas
        $permissions = [
            'manage questions',
            'manage answers',
            'manage players',
            'start game',
            'view leaderboard'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 👑 2. Criação das roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $playerRole = Role::firstOrCreate(['name' => 'player']);

        // Vincula permissões às roles
        $adminRole->syncPermissions($permissions);
        $playerRole->syncPermissions(['start game', 'view leaderboard']);

        // 👤 3. Criação do usuário administrador padrão
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@showdomilhao.local'],
            [
                'name' => 'Admin',
                'nickname' => 'Admin',
                'password' => Hash::make('admin123'),
                'best_prize' => 0,
                'score' => 0,
            ]
        );
        $adminUser->assignRole($adminRole);

        // 🧑 4. Criação de um jogador de exemplo
        $playerUser = User::firstOrCreate(
            ['email' => 'player@showdomilhao.local'],
            [
                'name' => 'Jogador Teste',
                'nickname' => 'JogadorTeste',
                'password' => Hash::make('player123'),
                'best_prize' => 0,
                'score' => 0,
            ]
        );
        $playerUser->assignRole($playerRole);

        $this->command->info('✅ Roles, permissões e usuários padrão criados com sucesso!');
    }
}
