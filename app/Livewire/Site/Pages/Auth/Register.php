<?php

namespace App\Livewire\Site\Pages\Auth;

use App\Http\DTO\Auth\RegisterDTO;
use App\Services\Auth\LoginService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Throwable;

class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório!',
            'name.string' => 'O campo nome deve ser uma string!',
            'email.required' => 'O campo email é obrigatório!',
            'email.string' => 'O campo email deve ser uma string!',
            'email.email' => 'O campo email é inválido!',
            'password.required' => 'O campo senha é obrigatório!',
            'password.string' => 'O campo senha deve ser uma string!',
            'password.min' => 'O campo senha deve ser de no mínimo 8 caracteres!',
        ];
    }

    public function store()
    {
        $this->validate();

        try {
            $params = (object) [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
            ];

            $dto = RegisterDTO::from($params);

            $user = app(LoginService::class)->HasRegister($dto);

            if ($user) {
                Auth::login($user);

                return redirect()->route('site.home');
            }
        } catch (Exception|Throwable $e) {
            Log::error('Error: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            toastr()
                ->timeOut(2000)
                ->error('Erro ao tentar registrar!');
        }
    }

    public function render()
    {
        return view('livewire.site.pages.auth.register');
    }
}
