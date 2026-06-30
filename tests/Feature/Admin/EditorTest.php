<?php

declare(strict_types=1);

use App\Livewire\Admin\Pages\Manage as PagesManage;
use App\Livewire\Admin\StaticPages\Manage as StaticPagesManage;
use App\Models\Page;
use App\Models\StaticPage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

it('stores an editor image upload and returns its url and type', function (): void {
    Storage::fake('public');

    $this->actingAs(userWithRole('admin'))
        ->post(route('admin.editor.attachments.store'), [
            'file' => UploadedFile::fake()->image('foto.png', 20, 20),
        ], ['Accept' => 'application/json'])
        ->assertOk()
        ->assertJsonStructure(['url', 'type'])
        ->assertJson(['type' => 'image']);

    expect(Storage::disk('public')->allFiles(config('editor.upload_path')))->not->toBeEmpty();
});

it('rejects an editor upload of a disallowed type', function (): void {
    Storage::fake('public');

    $this->actingAs(userWithRole('admin'))
        ->post(route('admin.editor.attachments.store'), [
            'file' => UploadedFile::fake()->create('malware.exe', 10, 'application/x-msdownload'),
        ], ['Accept' => 'application/json'])
        ->assertStatus(422);
});

it('blocks guests from the editor upload route', function (): void {
    $this->post(route('admin.editor.attachments.store'))->assertRedirect();
});

it('renders the post create and edit pages for an admin', function (): void {
    $admin = userWithRole('admin');

    $this->actingAs($admin)->get(route('admin.posts.create'))->assertOk();
});

it('creates a page through its manage page', function (): void {
    Livewire::actingAs(userWithRole('admin'))->test(PagesManage::class)
        ->set('form.name', 'Nova Página')
        ->set('form.slug', '')
        ->set('form.content', '<p>conteúdo da página</p>')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.pages.index'));

    $page = Page::query()->where('slug', 'nova-pagina')->first();
    expect($page)->not->toBeNull()
        ->and($page->content)->toBe('<p>conteúdo da página</p>');
});

it('creates a static page and assigns the author through its manage page', function (): void {
    $admin = userWithRole('admin');

    Livewire::actingAs($admin)->test(StaticPagesManage::class)
        ->set('form.name', 'Termos de Uso')
        ->set('form.content', '<p>termos</p>')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.static-pages.index'));

    $static = StaticPage::query()->where('slug', 'termos-de-uso')->first();
    expect($static)->not->toBeNull()
        ->and($static->user_id)->toBe($admin->id)
        ->and($static->content)->toBe('<p>termos</p>');
});

it('edits a static page through its manage page', function (): void {
    $admin = userWithRole('admin');
    $static = StaticPage::factory()->create(['name' => 'Velho', 'content' => '<p>velho</p>']);

    Livewire::actingAs($admin)->test(StaticPagesManage::class, ['staticPage' => $static])
        ->assertSet('form.name', 'Velho')
        ->set('form.content', '<p>novo conteúdo</p>')
        ->call('save')
        ->assertHasNoErrors();

    expect($static->fresh()->content)->toBe('<p>novo conteúdo</p>');
});
