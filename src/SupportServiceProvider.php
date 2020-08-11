<?php

namespace Hongyukeji\LaravelSupport;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class SupportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        $this->mergeConfigFrom(
            __DIR__ . '/../config/auth.php', 'auth'
        );

        $this->publishes([
            __DIR__ . '/../config/validation.php' => config_path('validation.php'),
        ], 'validation_config');

        $this->mergeConfigFrom(
            __DIR__ . '/../config/validation.php', 'validation'
        );

        // 手机号验证规则
        Validator::extend('mobile', function ($attribute, $value, $parameters, $validator) {
            $regex = config('validation.mobile', '/^1\d{10}$/');
            return preg_match($regex, $value);
        });

        // 用户名验证规则：用户名只能由数字、字母、中文汉字及下划线组成，不能包含特殊符号。
        Validator::extend('username', function ($attribute, $value, $parameters, $validator) {
            $regex = config('validation.username', '/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]+$/u');
            return preg_match($regex, $value);
        });

        // 用户名禁止出现关键词规则
        Validator::extend('username_disable_keyword', function ($attribute, $value, $parameters, $validator) {
            $censor_names = explode(',', config('validation.username_disable_keyword', 'admin,administrator'));
            return Str::contains($value, $censor_names) ? false : true;
        });

        // 价格验证规则
        Validator::extend('price', function ($attribute, $value, $parameters, $validator) {
            $regex = config('validation.price', '/^(?!0\d|[0.]+$)\d{1,8}(\.\d{1,2})?$/');
            return preg_match($regex, $value);
        });

        // 添加集合分页支持,使用: $items->paginate(10);
        if (!Collection::hasMacro('paginate')) {
            Collection::macro('paginate',
                function ($perPage = 15, $page = null, $options = []) {
                    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
                    return (new LengthAwarePaginator(
                        $this->forPage($page, $perPage), $this->count(), $perPage, $page, $options))
                        ->withPath('');
                });
        }
    }
}
