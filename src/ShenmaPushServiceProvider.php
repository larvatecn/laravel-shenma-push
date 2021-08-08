<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Shenma\Push;

use Illuminate\Support\ServiceProvider;

/**
 * Class ShenmaPushServiceProvider
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ShenmaPushServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands('command.shenma.push');
            $this->commands('command.shenma.push.retry');
            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'shenma-push');
        }

        \Larva\Shenma\Push\ShenmaPush::observe(\Larva\Shenma\Push\ShenmaPushObserver::class);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommand();
    }

    /**
     * Register the MNS queue command.
     * @return void
     */
    private function registerCommand()
    {
        $this->app->singleton('command.shenma.push', function () {
            return new \Larva\Shenma\Push\Commands\Push();
        });

        $this->app->singleton('command.shenma.push.retry', function () {
            return new \Larva\Shenma\Push\Commands\PushRetry();
        });
    }

}