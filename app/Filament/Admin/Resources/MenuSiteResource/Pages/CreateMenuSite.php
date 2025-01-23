<?php

namespace App\Filament\Admin\Resources\MenuSiteResource\Pages;

use App\Enums\Status;
use App\Filament\Admin\Resources\MenuSiteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMenuSite extends CreateRecord
{
    protected static string $resource = MenuSiteResource::class;

    protected static ?string $title = 'Novo Menu';
    protected static ?string $navigationLabel = 'Novo Menu';

    protected function getCreateAnotherFormAction(): Actions\Action
    {
        return Actions\Action::make('createAnother')
            ->hidden();
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $params = (object) $data;

        $menu = new \App\Archicture\Entities\MenusSites\Models\MenuSite();

        $menu->name = $params->name;
        $menu->route = $params->route;
        $menu->description = $params->description;
        $menu->user_id = auth()->id();
        $menu->status = Status::INACTIVE;

        $menu->save();

        $controllerFile = base_path('/app/Http/Controllers/Web/Site/Pages/'.str($menu->route)->ucfirst().'Controller.php');

        if (!file_exists($controllerFile)) {
            $file = fopen($controllerFile, "w+");
            fwrite($file, '<?php

namespace App\Http\Controllers\Web\Site\Pages;

use App\Http\Controllers\Controller;
use App\Archicture\Entities\MenusSites\Actions\ListMenusSitesAction;
use App\Http\Controllers\Web\WebBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ' . str($menu->route)->ucfirst() . 'Controller extends WebBaseController
{
    public function __construct(ListMenusSitesAction $listMenusSitesAction)
    {
        parent::__construct($listMenusSitesAction);
    }
}');
        }

        $route = base_path('/routes/web.php');

        if (file_exists($route)) {
            $file = fopen($route, "a");
            fwrite($file, "\n" . "Route::get('/{$menu->route}', [\\App\\Http\\Controllers\\Web\\Site\\Pages\\" . str($menu->route)->ucfirst() . "Controller::class, 'index'])->name('{$menu->route}');");
            fclose($file);
        }

        return $menu;
    }
}
