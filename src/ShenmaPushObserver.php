<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Shenma\Push;

use Larva\Shenma\Push\Jobs\PushJob;

/**
 * 模型观察者
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ShenmaPushObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param ShenmaPushModel $push
     * @return void
     */
    public function created(ShenmaPushModel $push)
    {
        PushJob::dispatch($push);
    }
}