<?php

namespace Shengfai\LaravelAdmin;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Shengfai\LaravelAdmin\Validator as CValidator;
use Shengfai\LaravelAdmin\DataTable\DataTable;
use Shengfai\LaravelAdmin\Http\Middleware\LogActivity;
use Shengfai\LaravelAdmin\Fields\Factory as FieldFactory;
use Shengfai\LaravelAdmin\Config\Factory as ConfigFactory;
use Shengfai\LaravelAdmin\Actions\Factory as ActionFactory;
use Shengfai\LaravelAdmin\DataTable\Columns\Factory as ColumnFactory;

class AdminServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        // 注册视图
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');
        
        $this->fixConfigAndRouteCacheIfNeeded();
        
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'admin');
        
        $this->registerPublishing();
        
        $this->setLocale();
        
        $this->app['events']->dispatch('admin.ready');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // include our view composers, and routes to avoid issues with catch-all routes defined by users
        include __DIR__ . '/viewComposers.php';
        include __DIR__ . '/helpers.php';
        
        $this->fixConfigAndRouteCacheIfNeeded();
        
        // Load route with web middleware
        Route::group([
            'domain' => config('administrator.domain', null),
            'prefix' => config('administrator.prefix'),
            'middleware' => LogActivity::class
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        });
        
        // the admin validator
        $this->app->singleton('admin.validator', function ($app) {
            // get the original validator class so we can set it back after creating our own
            $originalValidator = Validator::make([], []);
            $originalValidatorClass = get_class($originalValidator);
            // temporarily override the core resolver
            Validator::resolver(function ($translator, $data, $rules, $messages) use($app) {
                $validator = new CValidator($translator, $data, $rules, $messages);
                $validator->setUrlInstance($app->make('url'));
                return $validator;
            });
            // grab our validator instance
            $validator = Validator::make([], []);
            // set the validator resolver back to the original validator
            Validator::resolver(function ($translator, $data, $rules, $messages) use($originalValidatorClass) {
                return new $originalValidatorClass($translator, $data, $rules, $messages);
            });
            // return our validator instance
            return $validator;
        });
        
        // set up the shared instances
        $this->app->singleton('admin.config_factory', function ($app) {
            return new ConfigFactory($app->make('admin.validator'), Validator::make([], []), config('administrator'));
        });
        $this->app->singleton('admin.field_factory', function ($app) {
            return new FieldFactory($app->make('admin.validator'), $app->make('admin.item_config'), $app->make('db'));
        });
        $this->app->singleton('admin.datatable', function ($app) {
            $dataTable = new DataTable($app->make('admin.column_factory'), $app->make('admin.field_factory'));
            return $dataTable;
        });
        $this->app->singleton('admin.column_factory', function ($app) {
            return new ColumnFactory($app->make('admin.validator'), $app->make('admin.item_config'), $app->make('db'));
        });
        $this->app->singleton('admin.action_factory', function ($app) {
            return new ActionFactory($app->make('admin.validator'), $app->make('admin.item_config'), $app->make('db'));
        });
        $this->app->singleton('admin.menu', function ($app) {
            return new Menu($app->make('config'), $app->make('admin.config_factory'));
        });
        
        // 注册命令
        $this->commands('Shengfai\LaravelAdmin\AdminCommand');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'admin.validator',
            'admin.config_factory',
            'admin.field_factory',
            'admin.datatable',
            'admin.column_factory',
            'admin.action_factory',
            'admin.menu'
        ];
    }

    protected function fixConfigAndRouteCacheIfNeeded()
    {
        // Hack for laravel config cache
        $file = config_path('administrator.php');
        if (file_exists($file)) {
            $this->mergeConfigFrom($file, 'administrator');
            if (app()->runningInConsole()) {
                $configs = \Config::get('administrator');
                $configs = $this->filter_recursive($configs);
                \Config::set('administrator', $configs);
            }
        }
    }

    /**
     * Sets the locale if it exists in the session and also exists in the locales option.
     */
    protected function setLocale()
    {
        if ($locale = $this->app->session->get('admin.locale')) {
            $this->app->setLocale($locale);
        }
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
            ]);
            
            $this->publishes([
                __DIR__ . '/../resources/lang' => resource_path('lang')
            ]);
            
            $this->publishes([
                __DIR__ . '/Models/User.php' => app_path('Models/User.php')
            ]);
            
            $this->publishes([
                __DIR__ . '/../database/migrations/alter_activity_log_table.php' => database_path("/migrations/{$timestamp}_alter_activity_log_table.php"),
                __DIR__ . '/../database/migrations/alter_notifications_table.php' => database_path("/migrations/{$timestamp}_alter_notifications_table.php"),
                __DIR__ . '/../database/migrations/alter_permissions_table.php' => database_path("/migrations/{$timestamp}_alter_permissions_table.php"),
                __DIR__ . '/../database/migrations/alter_users_table.php' => database_path("/migrations/{$timestamp}_alter_users_table.php"),
                __DIR__ . '/../database/migrations/alter_tags_table.php' => database_path("/migrations/{$timestamp}_alter_tags_table.php"),
                __DIR__ . '/../database/migrations/create_admin_tables.php' => database_path("/migrations/{$timestamp}_create_admin_tables.php"),
                __DIR__ . '/../database/migrations/seed_admin_data.php' => database_path("/migrations/{$timestamp}_seed_admin_data.php"),
                __DIR__ . '/../database/migrations/seed_permissions_data.php' => database_path("/migrations/{$timestamp}_seed_permissions_data.php")
            ]);
            
            $this->publishes([
                __DIR__ . '/../resources/assets' => public_path('/admin')
            ]);
        }
    }

    protected function filter_recursive(&$array)
    {
        foreach ($array as $key => $item) {
            is_array($item) && $array[$key] = $this->filter_recursive($item);
            if (is_callable($array[$key])) {
                unset($array[$key]);
            }
        }
        return $array;
    }
}
