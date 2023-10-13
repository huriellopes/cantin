<?php

namespace App\Http\Controllers\Web\Users;

use App\Archicture\Entities\Users\Models\User;
use App\Http\Controllers\Controller;
use App\Traits\Utils;
use Illuminate\Auth\Access\AuthorizationException;
use Exception;

class ViewUsersController extends Controller
{
    use Utils;


    public function __construct(
        protected string $viewPath = 'SuperAdmin.Users.',
    ){}

    public function __invoke()
    {
        $this->authorize('view', User::class);
        try {
            return view($this->viewPath.'index');
        } catch (Exception $exception) {}
    }
}
