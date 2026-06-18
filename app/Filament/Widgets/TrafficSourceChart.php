<?php

namespace App\Filament\Widgets;

use App\Models\VisitorLog;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Str;

class TrafficSourceChart extends ChartWidget
{
    protected static ?string $heading = 'Sumber Trafik Teratas';
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = 'md';

    protected function getData(): array
    {
        $sources = VisitorLog::selectRaw("CASE
            WHEN referer IS NULL OR referer = '' THEN 'Direct'
            WHEN LOWER(referer) LIKE '%google%' THEN 'Google'
            WHEN LOWER(referer) LIKE '%instagram%' THEN 'Instagram'
            WHEN LOWER(referer) LIKE '%facebook%' THEN 'Facebook'
            WHEN LOWER(referer) LIKE '%youtube%' THEN 'YouTube'
            ELSE 'Other' END as source, COUNT(*) as total")
            ->groupBy('source')
            ->pluck('total', 'source')
            ->toArray();

        $labels = ['Direct', 'Google', 'Instagram', 'Facebook', 'YouTube', 'Other'];
        $data = [];

        foreach ($labels as $label) {
            $data[] = $sources[$label] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sumber Trafik',
                    'data' => $data,
                    'backgroundColor' => [
                        '#7c3aed', // purple
                        '#06b6d4', // cyan
                        '#c084fc', // light purple
                        '#38bdf8', // sky
                        '#ef4444', // red
                        '#94a3b8', // gray
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
