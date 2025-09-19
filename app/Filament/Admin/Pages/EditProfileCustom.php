<?php

namespace App\Filament\Admin\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Log;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class EditProfileCustom extends Page
{
    use InteractsWithFormActions;

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    protected static string | UnitEnum | null $navigationGroup = 'Gestão';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Meu Perfil';

    protected static ?string $title = 'Meu Perfil';

    protected static ?string $slug = 'edit-profile';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-user';

    public ?array $data = [];

    public function mount(): void
    {
        /** @var User $user */
        $user = Filament::auth()->user();

        $this->form->fill([
            'name' => $user->name,
            'slug' => $user->slug,
            'email' => $user->email,
        ]);
    }

    public function form(Schema $form) : Schema
    {
        return $form
            ->components([
                Fieldset::make()
                    ->label('Dados Pessoais')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->readOnly()
                            ->email()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('old_password')
                            ->label('Senha Atual')
                            ->password()
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Nova Senha')
                            ->password()
                            ->same('password_confirmation')
                            ->required(fn ($get) => !empty($get('old_password')))
                            ->maxLength(255),
                        TextInput::make('password_confirmation')
                            ->label('Confirmar Nova Senha')
                            ->password()
                            ->same('password')
                            ->required(fn ($get) => !empty($get('password')))
                            ->maxLength(255),
                    ]),
            ])->statePath('data');
    }

    public function getFormActions() : array
    {
        return [
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    public function save() : void
    {
        $this->validate();

        try {
            /* @var User $user */
            $user = Filament::auth()->user();

            $user->name = $this->data['name'];
            $user->slug = $this->data['slug'];

//            if (empty($this->data['old_password'])) {
//                Notification::make()
//                    ->warning()
//                    ->title('Por favor, insira sua senha atual.')
//                    ->send();
//                return;
//            }

            if (!empty($this->data['old_password']) && $this->data['password'] !== $this->data['old_password'] && $this->data['password'] === $this->data['password_confirmation']) {
                $user->password = bcrypt($this->data['password']);
            }

            $user->save();
            $user->refresh();

            Notification::make()
                ->title('Perfil atualizado com sucesso!')
                ->success()
                ->send();

            $this->redirectRoute('filament.admin.pages.dashboard');
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar o perfil: '. $e->getMessage());
            Notification::make()
                ->title('Erro ao atualizar perfil!')
                ->body('Por favor, tente novamente. Se o erro persistir, entre em contato com o suporte.')
                ->danger()
                ->send();
        }
    }

    protected string $view = 'filament.admin.pages.edit-profile-custom';
}
