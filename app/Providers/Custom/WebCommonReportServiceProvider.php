<?php

namespace App\Providers\Custom;

use Illuminate\Support\ServiceProvider;
use App\Services\Custom\WebCommonReportServiceInterface;
use App\Services\Custom\WebCommonReportService;

class WebCommonReportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind(WebCommonReportServiceInterface::class,WebCommonReportService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
