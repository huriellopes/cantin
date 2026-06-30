<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    /**
     * Corrige o slug do papel de super administrador.
     *
     * A migration original inseria o slug 'super-user' em produção, mas toda a
     * lógica de autorização (middleware role, LoginController, policies) e o
     * seeder usam 'super-admin'. Esse descasamento fazia o super admin receber
     * 403 no /admin e não ser redirecionado após o login.
     */
    public function up(): void
    {
        DB::table('roles')
            ->where('slug', 'super-user')
            ->update([
                'slug' => 'super-admin',
                'name' => 'Super Admin',
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')
            ->where('slug', 'super-admin')
            ->update([
                'slug' => 'super-user',
                'name' => 'Super User',
            ]);
    }
};
