<?php

declare(strict_types=1);

use App\Enum\Status;
use App\Livewire\Admin\DeletedModels\Index;
use App\Models\Category;
use Livewire\Livewire;
use Spatie\DeletedModels\Models\DeletedModel;

it('forbids non super-admins', function (): void {
    $this->actingAs(userWithRole('admin'))
        ->get('/admin/deleted-models')
        ->assertForbidden();
});

it('lists and restores a deleted record', function (): void {
    $category = Category::query()->create(['name' => 'Temp', 'slug' => 'temp', 'status' => Status::ACTIVE]);
    $key = $category->getKey();
    $category->delete();

    expect(DeletedModel::query()->count())->toBe(1)
        ->and(Category::query()->count())->toBe(0);

    $deleted = DeletedModel::query()->first();

    Livewire::actingAs(userWithRole('super-admin'))
        ->test(Index::class)
        ->call('restore', $deleted->id);

    expect(Category::query()->whereKey($key)->exists())->toBeTrue()
        ->and(DeletedModel::query()->count())->toBe(0);
});

it('permanently deletes a record', function (): void {
    $category = Category::query()->create(['name' => 'Temp2', 'slug' => 'temp2', 'status' => Status::ACTIVE]);
    $category->delete();
    $deleted = DeletedModel::query()->first();

    Livewire::actingAs(userWithRole('super-admin'))
        ->test(Index::class)
        ->call('forceDelete', $deleted->id);

    expect(DeletedModel::query()->count())->toBe(0)
        ->and(Category::query()->count())->toBe(0);
});
