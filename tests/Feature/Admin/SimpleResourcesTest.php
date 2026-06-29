<?php

use App\Enum\Status;
use App\Models\Category;
use App\Models\NationsTerreiro;
use App\Models\StaticPage;
use Livewire\Livewire;

it('opens each simple resource page', function (string $path) {
    $this->actingAs(userWithRole('admin'))->get($path)->assertOk();
})->with([
    '/admin/categories',
    '/admin/nations',
    '/admin/type-terreiros',
    '/admin/type-peoples',
    '/admin/type-external-links',
    '/admin/pages',
    '/admin/static-pages',
]);

it('creates a category with auto slug and active status', function () {
    Livewire::actingAs(userWithRole('admin'))
        ->test(App\Livewire\Admin\Categories\Index::class)
        ->call('create')
        ->set('form.name', 'Notícias')
        ->set('form.slug', '')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSet('showModal', false);

    $category = Category::query()->first();
    expect($category->name)->toBe('Notícias')
        ->and($category->slug)->toBe('noticias')
        ->and($category->status)->toBe(Status::ACTIVE);
});

it('validates required fields on simple resources', function () {
    Livewire::actingAs(userWithRole('admin'))
        ->test(App\Livewire\Admin\Nations\Index::class)
        ->call('create')
        ->set('form.name', '')
        ->set('form.slug', '')
        ->call('save')
        ->assertHasErrors(['form.name']);
});

it('toggles a category status', function () {
    $category = Category::query()->create(['name' => 'X', 'slug' => 'x', 'status' => Status::ACTIVE]);

    Livewire::actingAs(userWithRole('admin'))
        ->test(App\Livewire\Admin\Categories\Index::class)
        ->call('toggleStatus', $category->id);

    expect($category->fresh()->status)->toBe(Status::INACTIVE);
});

it('creates a nation without status', function () {
    Livewire::actingAs(userWithRole('admin'))
        ->test(App\Livewire\Admin\Nations\Index::class)
        ->call('create')
        ->set('form.name', 'Angola')
        ->set('form.slug', 'angola')
        ->call('save')
        ->assertHasNoErrors();

    expect(NationsTerreiro::query()->where('slug', 'angola')->exists())->toBeTrue();
});

it('sets the author when creating a static page', function () {
    $admin = userWithRole('super-admin');

    Livewire::actingAs($admin)
        ->test(App\Livewire\Admin\StaticPages\Index::class)
        ->call('create')
        ->set('form.name', 'Sobre')
        ->set('form.slug', 'sobre')
        ->set('form.content', 'Conteúdo da página.')
        ->call('save')
        ->assertHasNoErrors();

    expect(StaticPage::query()->where('slug', 'sobre')->value('user_id'))->toBe($admin->id);
});
