<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enum\Role as RoleEnum;
use App\Enum\Status;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuários fictícios apenas fora de produção (apoio a desenvolvimento/testes).
        if (!app()->isProduction()) {
            User::factory()->count(100)->create();
        }

        $this->seedAdmins();
    }

    /**
     * Cria os administradores de forma idempotente, com credenciais vindas do ambiente.
     * Em produção, defina SEED_SUPER_* e SEED_ADMIN_* no .env (sem segredos no código).
     */
    private function seedAdmins(): void
    {
        $admins = [
            [
                'name' => env('SEED_SUPER_NAME', 'Super Admin'),
                'email' => env('SEED_SUPER_EMAIL', 'super@cantin.test'),
                'password' => env('SEED_SUPER_PASSWORD', 'password'),
                'role_id' => RoleEnum::SUPER,
            ],
            [
                'name' => env('SEED_ADMIN_NAME', 'Admin'),
                'email' => env('SEED_ADMIN_EMAIL', 'admin@cantin.test'),
                'password' => env('SEED_ADMIN_PASSWORD', 'password'),
                'role_id' => RoleEnum::ADMIN,
            ],
        ];

        foreach ($admins as $admin) {
            User::query()->firstOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'slug' => Str::slug($admin['name']) . '-' . Str::random(5),
                    'email_verified_at' => now(),
                    'password' => bcrypt($admin['password']),
                    'role_id' => $admin['role_id'],
                    'status' => Status::ACTIVE,
                ],
            );
        }
    }
}
