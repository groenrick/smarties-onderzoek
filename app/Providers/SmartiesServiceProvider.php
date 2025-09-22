<?php

declare(strict_types=1);

// app/Providers/SmartiesServiceProvider.php
namespace App\Providers;

use App\Repositories\SmartiesEntryRepository;
use App\Repositories\SmartiesEntryRepositoryInterface;
use App\Services\Contributor\ContributorService;
use App\Services\Contributor\ContributorServiceInterface;
use App\Services\SmartiesEntry\SmartiesEntryService;
use App\Services\SmartiesEntry\SmartiesEntryServiceInterface;
use App\Services\Statistics\StatisticsService;
use App\Services\Statistics\StatisticsServiceInterface;
use Illuminate\Support\ServiceProvider;

class SmartiesServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repository interfaces to implementations
        $this->app->bind(
            SmartiesEntryRepositoryInterface::class,
            SmartiesEntryRepository::class
        );

        // Bind service interfaces to implementations
        $this->app->bind(
            SmartiesEntryServiceInterface::class,
            SmartiesEntryService::class
        );

        $this->app->bind(
            StatisticsServiceInterface::class,
            StatisticsService::class
        );

        $this->app->bind(
            ContributorServiceInterface::class,
            ContributorService::class
        );
    }

    public function boot(): void
    {
        //
    }
}
