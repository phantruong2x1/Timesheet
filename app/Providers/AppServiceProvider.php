<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Sử dụng giao diện Bootstrap 4 để hiển thị các Link phân trang (paginaion link)
        Paginator::defaultView('vendor.pagination.bootstrap-5');
    }
}
