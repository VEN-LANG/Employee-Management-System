<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Filament\Resources\AttendanceResource\RelationManagers;
use App\Models\Attendance;
use App\Models\Employee;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('employee_id')
                    ->label('Employee')
                    ->options(function (callable $get) {
                        return Employee::all()->pluck('user.name', 'id');
                    })
//                    ->rules([
//                        function () {
//                            return function (string $attribute, $value, Closure $fail) {
//                                $today_attendance = (new Attendance())->query()->where('employee_id', $value)->whereBetween('date', [now()->startOfDay(), now()->endOfDay()])->first();
//                                if ($today_attendance) {
//                                    Notification::make()->warning()
//                                        ->title('Employee already checked in today')
//                                        ->body(''.(Employee::where('id',$value)->first()->user->name).' already checked in today.')->broadcast(Auth::user())->send();
//                                    $fail('This :attribute is already checked in today.');
//                                }
//                            };
//                        },
//                    ])
                    ->searchable()
                    ->reactive()
                    ->required(),
                Forms\Components\DatePicker::make('date')
                    ->reactive()
                    ->required(),
                Forms\Components\TimePicker::make('check_in')
                    ->reactive()
                    ->required(),
                Forms\Components\TimePicker::make('check_out')
                    ->reactive()->after('check-in'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.user.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in'),
                Tables\Columns\TextColumn::make('check_out'),
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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\DetachBulkAction::make()
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
