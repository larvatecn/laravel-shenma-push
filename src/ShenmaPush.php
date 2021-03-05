<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Shenma\Push;

use Larva\Shenma\Push\Jobs\UpdateJob;

/**
 * 推送快捷方法
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ShenmaPush
{
    /**
     * 推送 Url
     * @param string $url
     * @param string $type
     * @return mixed
     */
    public static function push(string $url, $type = ShenmaPushModel::TYPE_MIP)
    {
        return ShenmaPushModel::firstOrCreate(['url' => $url, 'type' => $type]);
    }

    /**
     * 推送 Url
     * @param string $url
     */
    public static function update(string $url)
    {
        if (($ping = ShenmaPushModel::query()->where('url', '=', $url)->first()) != null) {
            $ping->update(['status' => ShenmaPushModel::STATUS_PENDING]);
            UpdateJob::dispatch($ping);
        } else {
            static::push($url);
        }
    }
}