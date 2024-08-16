<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->options(function (callable $get) {
                        return User::all()->pluck('name', 'id');
                    })
                    ->searchable()
                    ->reactive()
                    ->required(),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required(),
                Forms\Components\Select::make('position_id')
                    ->label('Position')
                    ->options(function (callable $get) {
                        return Position::all()->pluck('title', 'id');
                    })
                    ->searchable()
                    ->reactive()
                    ->required(),
                Forms\Components\TextInput::make('salary')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('date_of_joining')
                    ->required(),
                Forms\Components\Select::make('department_id')
                    ->label('Department')
                    ->options(function (callable $get) {
                        return Department::all()->pluck('name', 'id');
                    })
                    ->searchable()
                    ->reactive()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('salary')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_joining')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\DetachBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
