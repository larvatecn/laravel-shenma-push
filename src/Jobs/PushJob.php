<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Shenma\Push\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Larva\Shenma\Push\ShenmaPushModel;
use Larva\Support\HttpResponse;

/**
 * 推送 Url
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PushJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 2;

    /**
     * @var ShenmaPushModel
     */
    protected $push;
    protected $site;
    protected $username;
    protected $token;

    /**
     * Create a new job instance.
     *
     * @param ShenmaPushModel $push
     */
    public function __construct(ShenmaPushModel $push)
    {
        $this->push = $push;
        $this->site = parse_url(config('app.url'), PHP_URL_HOST);
        if (function_exists('settings')) {
            $this->site = parse_url(config('app.url'), PHP_URL_HOST);
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
            if ($this->push->type == ShenmaPushModel::TYPE_MIP) {
                $response = Http::contentType('text/plain')->post("https://data.zhanzhang.sm.cn/push?site={$this->site}&user_name={$this->username}&resource_name=mip_add&token={$this->token}", [
                    'body' => $this->push->url
                ]);
                if (isset($response['returnCode']) && $response['returnCode'] != 200) {
                    $this->push->setFailure($response['errorMsg']);
                } else {
                    $this->push->setSuccess();
                }
            }
        } catch (\Exception $e) {
            $this->push->setFailure($e->getMessage());
        }
    }
}
