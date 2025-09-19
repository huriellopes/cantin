<?php

namespace App\Filament\Admin\Resources\Pages\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Fieldset;
use Filament\Schemas\Schema;

class PageForm
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
                            ->validationMessages([
                                'required' => 'O campo nome é obrigatório.'
                            ])
                            ->label('Nome'),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'required' => 'O campo slug é obrigatório',
                                'unique' => 'Já há um registro com esse slug.',
                            ])
                            ->label('Slug'),
                        RichEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->validationMessages([
                                'required' => 'O campo conteúdo é obrigatório.',
                            ])
                            ->label('Conteúdo'),
                    ]),
            ]);
    }
}
