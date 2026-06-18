<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class VisitorAnalytics extends Page
{
    protected static string $view = 'filament.pages.visitor-analytics';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Visitor Analytics';
    protected static ?int $navigationSort = 1;
}
