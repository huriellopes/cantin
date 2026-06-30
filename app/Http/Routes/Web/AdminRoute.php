<?php

declare(strict_types=1);

namespace App\Http\Routes\Web;

use App\Http\Controllers\Web\EditorAttachmentController;
use App\Http\Controllers\Web\ExportDownloadController;
use App\Livewire\Admin\Categories\Index as CategoriesIndex;
use App\Livewire\Admin\Comments\Index as CommentsIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\DeletedModels\Index as DeletedModelsIndex;
use App\Livewire\Admin\ExternalLinks\Index as ExternalLinksIndex;
use App\Livewire\Admin\ImpersonationLogs\Index as ImpersonationLogsIndex;
use App\Livewire\Admin\Nations\Index as NationsIndex;
use App\Livewire\Admin\Pages\Index as PagesIndex;
use App\Livewire\Admin\Pages\Manage as PagesManage;
use App\Livewire\Admin\PartnerEntities\Index as PartnerEntitiesIndex;
use App\Livewire\Admin\PasswordChange;
use App\Livewire\Admin\Posts\Index as PostsIndex;
use App\Livewire\Admin\Posts\Manage as PostsManage;
use App\Livewire\Admin\Profile\Index as ProfileIndex;
use App\Livewire\Admin\StaticPages\Index as StaticPagesIndex;
use App\Livewire\Admin\StaticPages\Manage as StaticPagesManage;
use App\Livewire\Admin\Terreiros\Index as TerreirosIndex;
use App\Livewire\Admin\TransPeoples\Index as TransPeoplesIndex;
use App\Livewire\Admin\TypeExternalLinks\Index as TypeExternalLinksIndex;
use App\Livewire\Admin\TypePeoples\Index as TypePeoplesIndex;
use App\Livewire\Admin\TypeTerreiros\Index as TypeTerreirosIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AdminRoute
{
    public static function web(): void
    {
        Route::name('admin.')
            ->prefix('admin')
            ->middleware(['auth', 'role:admin,super-admin', 'password.changed'])
            ->group(function (): void {
                Route::get('/', Dashboard::class)->name('dashboard');

                // Troca obrigatória de senha (senha padrão); liberada pelo middleware.
                Route::get('/change-password', PasswordChange::class)->name('password.change');

                Route::get('/profile', ProfileIndex::class)->name('profile');

                Route::get('/exports/{export}/download', [ExportDownloadController::class, 'download'])->name('exports.download');

                // Upload de mídia do editor rico (Quill).
                Route::post('/editor/attachments', EditorAttachmentController::class)->name('editor.attachments.store');

                Route::get('/terreiros', TerreirosIndex::class)->name('terreiros.index');
                Route::get('/comments', CommentsIndex::class)->name('comments.index');
                Route::get('/posts/create', PostsManage::class)->name('posts.create');
                Route::get('/posts/{post}/edit', PostsManage::class)->name('posts.edit');
                Route::get('/posts', PostsIndex::class)->name('posts.index');
                Route::get('/categories', CategoriesIndex::class)->name('categories.index');
                Route::get('/nations', NationsIndex::class)->name('nations.index');
                Route::get('/type-terreiros', TypeTerreirosIndex::class)->name('type-terreiros.index');
                Route::get('/type-peoples', TypePeoplesIndex::class)->name('type-peoples.index');
                Route::get('/type-external-links', TypeExternalLinksIndex::class)->name('type-external-links.index');
                Route::get('/external-links', ExternalLinksIndex::class)->name('external-links.index');
                Route::get('/trans-peoples', TransPeoplesIndex::class)->name('trans-peoples.index');
                Route::get('/partner-entities', PartnerEntitiesIndex::class)->name('partner-entities.index');
                Route::get('/pages/create', PagesManage::class)->name('pages.create');
                Route::get('/pages/{page}/edit', PagesManage::class)->name('pages.edit');
                Route::get('/pages', PagesIndex::class)->name('pages.index');
                Route::get('/static-pages/create', StaticPagesManage::class)->name('static-pages.create');
                Route::get('/static-pages/{staticPage}/edit', StaticPagesManage::class)->name('static-pages.edit');
                Route::get('/static-pages', StaticPagesIndex::class)->name('static-pages.index');

                // Apenas super-admin
                Route::middleware('role:super-admin')->group(function (): void {
                    Route::get('/users', UsersIndex::class)->name('users.index');
                    Route::get('/deleted-models', DeletedModelsIndex::class)->name('deleted-models.index');
                    Route::get('/impersonation-logs', ImpersonationLogsIndex::class)->name('impersonation-logs.index');
                });

                Route::post('/logout', function () {
                    Auth::logout();
                    request()->session()->invalidate();
                    request()->session()->regenerateToken();

                    return to_route('site.home');
                })->name('logout');
            });
    }
}
