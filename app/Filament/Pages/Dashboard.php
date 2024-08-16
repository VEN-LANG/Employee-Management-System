<?php

namespace App\Filament\Pages;

use App\Filament\Resources\ProjectResource\Widgets\ProjectOverviewChart;
use App\Filament\Resources\ProjectResource\Widgets\ProjectsWidget;
use App\Filament\Resources\UserResource\Widgets\UsersOverview;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            UsersOverview::class,
            ProjectsWidget::class,
            ProjectOverviewChart::class
        ];
    }
}
