<?php

namespace Shengfai\LaravelAdmin;

use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPublishing();
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            
            $timestamp = date('Y_m_d_His', (time() + 30));
            
            $this->publishes([
                __DIR__ . '/../config' => config_path()
            ], 'laravel-admin-config');
            
            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang')
            ], 'laravel-admin-lang');
            
            $this->publishes([
                __DIR__ . '/Models/User.php' => app_path('Models/User.php')
            ], 'laravel-admin-models');
            
            // $this->publishes([
            // __DIR__ . '/Controllers/' => app_path('Http/Controllers/Admin')
            // ], 'laravel-admin-controllers');
            
            $this->publishes([
                __DIR__ . '/../database/migrations/alter_activity_log_table.php' => database_path("/migrations/{$timestamp}_alter_activity_log_table.php"),
                __DIR__ . '/../database/migrations/alter_notifications_table.php' => database_path("/migrations/{$timestamp}_alter_notifications_table.php"),
                __DIR__ . '/../database/migrations/alter_permissions_table.php' => database_path("/migrations/{$timestamp}_alter_permissions_table.php"),
                __DIR__ . '/../database/migrations/alter_users_table.php' => database_path("/migrations/{$timestamp}_alter_users_table.php"),
                __DIR__ . '/../database/migrations/create_admin_tables.php' => database_path("/migrations/{$timestamp}_create_admin_tables.php"),
                __DIR__ . '/../database/migrations/seed_admin_data.php' => database_path("/migrations/{$timestamp}_seed_admin_data.php"),
                __DIR__ . '/../database/migrations/seed_permissions_data.php' => database_path("/migrations/{$timestamp}_seed_permissions_data.php")
            ], 'laravel-admin-migrations');
            
            $this->publishes([
                __DIR__ . '/../resources/assets' => public_path('/admin')
            ], 'laravel-admin-assets');
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // 注册命令
        $this->commands('Shengfai\LaravelAdmin\AdminCommand');
        
        // 注册路由
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        
        // 注册视图
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');
    }
}