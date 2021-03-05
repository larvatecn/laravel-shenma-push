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
            if ($this->push->type == ShenmaPushModel::TYPE_MIP) {
                /** @var HttpResponse $response */
                $response = Shenma::MIPPush(config('services.shenma.site'), config('services.shenma.username'), config('services.shenma.token'),$this->push->url);
                if ($response['returnCode'] != 200) {
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
