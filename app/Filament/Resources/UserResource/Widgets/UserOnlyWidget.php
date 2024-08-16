<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\Employee;
use App\Models\Leave;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserOnlyWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Number of Users', User::query()->withTrashed()->count())->description('All Users registered on this Application')->icon('heroicon-o-user-group'),
            Stat::make('Verified Users', User::query()->withTrashed()->whereNotNull('email_verified_at')->count())->description('All Users with Verified Accounts')->icon('clarity-employee-group-line'),
            Stat::make('Deleted Users', User::onlyTrashed()->count())
                ->description('Number of users who have been soft deleted')
                ->icon('forkawesome-user-times'),
            Stat::make('Employees On Leave', Leave::query()->where('status', '=', 'active')->count())->description('All Employees On Leave')->icon('pepicon-leave-circle-off')
        ];
    }
}
