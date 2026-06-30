<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class() extends Migration
{
    /**
     * Remove a role 'user'. O cadastro público foi descontinuado — apenas
     * super-admin e admin permanecem, criados pelo super-admin. Em produção
     * não há contas nessa role (FK users.role_id não é violada).
     */
    public function up(): void
    {
        DB::table('roles')->where('slug', 'user')->delete();
    }

    public function down(): void
    {
        DB::table('roles')->updateOrInsert(
            ['slug' => 'user'],
            ['name' => 'User'],
        );
    }
};
