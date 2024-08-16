<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\Employee;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Number of Users', User::query()->count())->description('All Users registered on this Application')->icon('heroicon-o-user-group'),
            Stat::make('Number of Employees', Employee::query()->count())->description('All Employees on this Application')->icon('clarity-employee-group-line'),
            Stat::make('Number of Projects', Employee::query()->count())->description('All Projects on this Application')->icon('fas-project-diagram')
        ];
    }
}
