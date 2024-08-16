<?php

namespace App\Filament\Resources\ProjectResource\Widgets;

use App\Models\Project;
use Filament\Forms\Components\DatePicker;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ProjectsWidget extends ApexChartWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $chartId = 'ProjectsWidget';

    protected static ?string $heading = 'Projects Chart';

    protected function getFilters(): array
    {
        return [
            'today' => 'Today',
            'week' => 'This Week',
            'month' => 'This Month',
            'year' => 'This Year',
        ];
    }

    protected function getOptions(): array
    {
        $filter = $this->filter ?? 'today'; // Default to 'month' if no filter is selected
        if ($this->filter == null) {
            $diff = date_diff(Carbon::parse($this->filterFormData['date_end']), Carbon::parse($this->filterFormData['date_start']))->days;
            if ($diff <= 0) {
                $filter = 'today';
            } else if ($diff <= 7) {
                $filter = 'week';
            } else if ($diff <= 30) {
                $filter = 'month';
            } else if ($diff > 30) {
                $filter = 'year';
            }
        }
        switch ($filter) {
            case 'today':
                $data = Trend::model(Project::class)
                    ->between(
                        start: now()->startOfDay(),
                        end: now()->endOfDay(),
                    )
                    ->perHour()
                    ->count();
                break;
            case 'week':
                $data = Trend::model(Project::class)
                    ->between(
                        start: now()->startOfWeek(),
                        end: now()->endOfWeek(),
                    )
                    ->perDay()
                    ->count();
                break;
            case 'year':
                $data = Trend::model(Project::class)
                    ->between(
                        start: now()->startOfYear(),
                        end: now()->endOfYear(),
                    )
                    ->perMonth()
                    ->count();
                break;
            case 'month':
            default:
                $data = Trend::model(Project::class)
                    ->between(
                        start: now()->startOfMonth(),
                        end: now()->endOfMonth(),
                    )
                    ->perDay()->count();
                break;
        }

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Projects',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'xaxis' => [
                'categories' => $this->formatLabels($data, $filter),
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => ['#6366f1'],
            'stroke' => [
                'curve' => 'smooth',
            ],
        ];
    }

    private function formatLabels($data, $filter)
    {
        switch ($filter) {
            case 'today':
                return $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('H:00'));
            case 'week':
                return $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('D'));
            case 'year':
                return $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('M'));
            case 'month':
            default:
                return $data->map(fn(TrendValue $value) => Carbon::parse($value->date)->format('d'));
        }
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('date_start')
                ->default(now()->subMonth()),
            DatePicker::make('date_end')
                ->default(now()),
        ];
    }
}