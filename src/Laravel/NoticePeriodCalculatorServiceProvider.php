<?php

declare(strict_types=1);

namespace URLCV\NoticePeriodCalculator\Laravel;

use Illuminate\Support\ServiceProvider;

/**
 * Laravel service provider for the Notice Period Calculator package.
 * Loads Blade views for frontend Alpine.js integration.
 */
class NoticePeriodCalculatorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'notice-period-calculator');
    }
}
