<?php

namespace App\Filament\Admin\Resources\StaticPages\Schemas;

use App\Enum\Status;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class StaticPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make()
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->columnSpanFull()
                            ->label('Nome'),
                        TextInput::make('slug')
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull()
                            ->prefix('http://cantinbr.com.br/')
                            ->required(),
                        Hidden::make('user_id')
                            ->default(auth()->id()),
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->maxWidth('full')
                            ->label('Conteúdo'),
                    ])
            ]);
    }
}
