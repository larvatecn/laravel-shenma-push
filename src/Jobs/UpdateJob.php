<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 * @license http://www.larva.com.cn/license/
 */

namespace Larva\Shenma\Push\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Larva\Shenma\Push\ShenmaPushModel;
use Larva\Support\HttpResponse;

/**
 * Class UpdateJob
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class UpdateJob implements ShouldQueue
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

    /**
     * Create a new job instance.
     *
     * @param ShenmaPushModel $push
     */
    public function __construct(ShenmaPushModel $push)
    {
        $this->push = $push;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            /** @var HttpResponse $response */
            $response = Shenma::MIPPush(config('services.shenma.site'), config('services.shenma.username'), config('services.shenma.token'), $this->push->url);
            if ($response['returnCode'] != 200) {
                $this->push->setFailure($response['errorMsg']);
            } else {
                $this->push->setSuccess();
            }
        } catch (\Exception $e) {
            $this->push->setFailure($e->getMessage());
        }
    }
}