<?php

declare(strict_types=1);

use App\Enum\Status;
use App\Livewire\Admin\Categories\Index as CategoriesIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Models\Category;
use App\Models\User;
use Livewire\Livewire;

it('opens a confirm modal and only deletes after confirmation', function () {
    $admin = userWithRole('super-admin');
    $target = userWithRole('user');

    $component = Livewire::actingAs($admin)->test(UsersIndex::class)
        ->call('confirmDelete', $target->id)
        ->assertSet('confirm.method', 'delete');

    expect(User::query()->whereKey($target->id)->exists())->toBeTrue(); // ainda não excluiu

    $component->call('confirmed');

    expect(User::query()->whereKey($target->id)->exists())->toBeFalse(); // excluiu após confirmar
});

it('cancels a pending confirmation without acting', function () {
    $category = Category::query()->create(['name' => 'X', 'slug' => 'x', 'status' => Status::ACTIVE]);

    Livewire::actingAs(userWithRole('super-admin'))->test(CategoriesIndex::class)
        ->call('confirmDelete', $category->id)
        ->call('cancelConfirm')
        ->assertSet('confirm', []);

    expect(Category::query()->whereKey($category->id)->exists())->toBeTrue();
});

it('populates the view modal', function () {
    $user = userWithRole('user');

    Livewire::actingAs(userWithRole('super-admin'))->test(UsersIndex::class)
        ->call('view', $user->id)
        ->assertSet('showView', true)
        ->assertSet('viewTitle', $user->name);
});

it('dispatches a toast on actions', function () {
    $category = Category::query()->create(['name' => 'Y', 'slug' => 'y', 'status' => Status::ACTIVE]);

    Livewire::actingAs(userWithRole('super-admin'))->test(CategoriesIndex::class)
        ->call('toggleStatus', $category->id)
        ->assertDispatched('toast');
});
