<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enum\Role as RoleEnum;
use App\Enum\Status;
use App\Models\Comment;
use App\Models\Dislike;
use App\Models\ExternalLink;
use App\Models\Like;
use App\Models\Page;
use App\Models\PartnerEntity;
use App\Models\Post;
use App\Models\StaticPage;
use App\Models\Terreiro;
use App\Models\TerreiroQuestion;
use App\Models\TransPeople;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Seed único da aplicação.
 *
 * - Dados essenciais (roles, localidades do IBGE e tabelas de apoio) são sempre
 *   criados — a app depende deles.
 * - Um super-admin (e um admin) são criados de forma idempotente via .env.
 * - Dados fictícios (faker) só são gerados FORA de produção, para dar volume ao
 *   ambiente de desenvolvimento/testes.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedRoles();
        $this->seedLocalidades();
        $this->seedLookups();
        $this->seedAdmins();

        if (!app()->isProduction()) {
            $this->seedFakeData();
        }
    }

    /**
     * Papéis de acesso (super-admin e admin) — ids 1 e 2 (ver App\Enum\Role).
     */
    private function seedRoles(): void
    {
        foreach ([['Super Admin', 'super-admin'], ['Admin', 'admin']] as [$name, $slug]) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $slug],
                ['name' => $name, 'updated_at' => now(), 'created_at' => now()],
            );
        }
    }

    /**
     * Estados e cidades vêm da API oficial do IBGE (fonte canônica).
     * Pulado em testes para não depender de rede.
     */
    private function seedLocalidades(): void
    {
        if (!app()->runningUnitTests()) {
            Artisan::call('localidades:sync');
        }
    }

    /**
     * Tabelas de apoio (taxonomias) usadas pelos cadastros.
     */
    private function seedLookups(): void
    {
        $this->insertLookup('type_peoples', [
            ['name' => 'Travesti', 'slug' => 'travesti', 'description' => ''],
            ['name' => 'Pessoa Trans', 'slug' => 'pessoa-trans', 'description' => ''],
            ['name' => 'Não Binária', 'slug' => 'nao-binaria', 'description' => ''],
            ['name' => 'Pessoa Cis', 'slug' => 'pessoa-cis', 'description' => 'Pessoa que não é trans'],
            ['name' => 'Não se aplica', 'slug' => 'nao-se-aplica', 'description' => 'Não se aplica o sexo da pessoa'],
        ]);

        $this->insertLookup('type_terreiros', [
            ['name' => 'Trans-inclusivos', 'slug' => 'trans-inclusivos', 'description' => 'Terreiro que é trans-inclusivo'],
            ['name' => 'Não se aplica', 'slug' => 'nao-se-aplica', 'description' => null],
        ]);

        $this->insertLookup('nations_terreiros', [
            ['name' => 'Candomblé Ketu', 'slug' => 'candomble-ketu'],
            ['name' => 'Candomblé Jeje', 'slug' => 'candomble-jeje'],
            ['name' => 'Candomblé Nagô', 'slug' => 'candomble-nago'],
            ['name' => 'Candomblé Angola', 'slug' => 'candomble-angola'],
            ['name' => 'Umbanda', 'slug' => 'umbanda'],
            ['name' => 'Tambor de Mina', 'slug' => 'tambor-de-mina'],
            ['name' => 'Xangô', 'slug' => 'xango'],
            ['name' => 'Batuque', 'slug' => 'batuque'],
            ['name' => 'Outros', 'slug' => 'outros'],
        ]);

        $this->insertLookup('type_external_links', [
            ['name' => 'Livros', 'slug' => 'livros'],
            ['name' => 'Apostilas', 'slug' => 'apostilas'],
            ['name' => 'E-books', 'slug' => 'e-books'],
        ]);

        $this->insertLookup('suggestions', [
            ['name' => 'Criticas', 'slug' => 'criticas', 'description' => 'Criticas construtivas e sugestões de melhorias'],
            ['name' => 'Dúvidas', 'slug' => 'duvidas', 'description' => null],
            ['name' => 'Indicações', 'slug' => 'indicacoes', 'description' => null],
        ]);

        $this->insertLookup('categories', [
            ['name' => 'Terreiros', 'slug' => 'terreiros'],
            ['name' => 'Candomblé', 'slug' => 'candomble'],
        ]);
    }

    /**
     * Insere linhas de uma taxonomia de forma idempotente (por slug).
     *
     * @param  array<int, array<string, mixed>>  $rows
     */
    private function insertLookup(string $table, array $rows): void
    {
        foreach ($rows as $row) {
            DB::table($table)->updateOrInsert(
                ['slug' => $row['slug']],
                array_merge($row, ['updated_at' => now(), 'created_at' => now()]),
            );
        }
    }

    /**
     * Administradores idempotentes; credenciais vêm do ambiente
     * (defina SEED_SUPER_* e SEED_ADMIN_* no .env, sem segredos no código).
     */
    private function seedAdmins(): void
    {
        $admins = [
            ['name' => env('SEED_SUPER_NAME', 'Super Admin'), 'email' => env('SEED_SUPER_EMAIL', 'super@cantin.test'), 'password' => env('SEED_SUPER_PASSWORD', 'password'), 'role_id' => RoleEnum::SUPER],
            ['name' => env('SEED_ADMIN_NAME', 'Admin'), 'email' => env('SEED_ADMIN_EMAIL', 'admin@cantin.test'), 'password' => env('SEED_ADMIN_PASSWORD', 'password'), 'role_id' => RoleEnum::ADMIN],
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

    /**
     * Dados fictícios para desenvolvimento/testes (não roda em produção).
     */
    private function seedFakeData(): void
    {
        $users = User::factory()->count(20)->create();

        $posts = Post::factory()->count(15)->create();
        Comment::factory()->count(40)->recycle($users)->recycle($posts)->create();
        Like::factory()->count(50)->recycle($users)->recycle($posts)->create();
        Dislike::factory()->count(15)->recycle($users)->recycle($posts)->create();

        PartnerEntity::factory()->count(12)->create();
        TransPeople::factory()->count(12)->create();
        ExternalLink::factory()->count(10)->recycle($users)->create();
        Page::factory()->count(5)->create();
        StaticPage::factory()->count(5)->recycle($users)->create();

        // Terreiros com o questionário relacionado (hasOne question).
        Terreiro::factory()->count(15)->has(TerreiroQuestion::factory(), 'question')->create();
    }
}
