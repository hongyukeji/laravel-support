<?php

namespace Hongyukeji\LaravelSupport\Middleware;

use Closure;
use Hongyukeji\LaravelSupport\Constants\CacheConstant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserLastActivityLog
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $expires_at = Carbon::now()->addMinutes(5);
            Cache::put(CacheConstant::USER_LAST_ACTIVITY_LOG_PREFIX . Auth::id(), time(), $expires_at);
        }
        return $next($request);
    }

    /*
     * User 模型中添加如下方法
     * 获取用户最后活动时间 auth()->user()->last_activity_time
     */
    /*public function getLastActivityTimeAttribute()
    {
        return Cache::has(CacheConstant::USER_LAST_ACTIVITY_LOG_PREFIX . $this->id);
    }*/
}
