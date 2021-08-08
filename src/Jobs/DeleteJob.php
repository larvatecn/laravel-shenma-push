<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Shenma\Push\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

/**
 * Class DeleteJob
 * @author Tongle Xu <xutongle@gmail.com>
 */
class DeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 2;

    /**
     * @var string
     */
    protected $url;
    protected $site;
    protected $username;
    protected $token;

    /**
     * Create a new job instance.
     *
     * @param string $url
     */
    public function __construct(string $url)
    {
        $this->url = $url;
        $this->site = parse_url(config('app.url'), PHP_URL_HOST);
        if (function_exists('settings')) {
            $this->username = settings('system.shenma_username');
            $this->token = settings('system.shenma_token');
        } else {
            $this->username = config('services.shenma.username');
            $this->token = config('services.shenma.token');
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Http::acceptJson()
                ->withBody($this->url, 'text/plain')
                ->post("https://data.zhanzhang.sm.cn/push?site={$this->site}&user_name={$this->username}&resource_name=mip_clean&token={$this->token}");
        } catch (\Exception $e) {

        }
    }
}