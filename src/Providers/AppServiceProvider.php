<?php

namespace Hongyukeji\LaravelSupport\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 自动发现并注册 ServiceProvider
        foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . '*.php') as $file) {
            $class_namespace = __NAMESPACE__;
            $class_service_provider = "{$class_namespace}\\" . Str::before(class_basename($file), '.php');
            // 判断类名是否存在，且是否以给定的值结尾，且不等于当前类名
            if (class_exists($class_service_provider) &&
                Str::endsWith($class_service_provider, 'ServiceProvider') &&
                $class_service_provider !== self::class) {
                $this->app->register($class_service_provider);
            }
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
