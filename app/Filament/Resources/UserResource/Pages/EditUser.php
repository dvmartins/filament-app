<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Components\TextInput;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('change_password')
                ->form([
                    TextInput::make('password')
                        ->required()
                        ->password()
                        ->rule(Password::default()),
                    TextInput::make('password_confirmation')
                        ->password()
                        ->same('password')
                        ->rule(Password::default())
                ])
                ->action(function(array $data) {
                    $this->record->update([
                        'password' => bcrypt($data['password'])
                    ]);
                    $this->notify('sucess', 'Senha atualizada com sucesso!');
                }),
            Actions\DeleteAction::make(),

        ];
    }
}
