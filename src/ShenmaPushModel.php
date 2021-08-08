<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Shenma\Push;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * 神马搜索Ping
 * @property int $id
 * @property string $type
 * @property string $url
 * @property int $status
 * @property string $msg
 * @property int $failures
 * @property Carbon|null $push_at
 *
 * @property-read boolean $failure
 * @method static Builder|ShenmaPush failure()
 * @method static Builder|ShenmaPush pending()
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class ShenmaPushModel extends Model
{
    const UPDATED_AT = null;

    const TYPE_MIP = 'mip';//站长平台 MIP

    const STATUS_PENDING = 0b0;//待推送
    const STATUS_SUCCESS = 0b1;//正常
    const STATUS_FAILURE = 0b10;//失败

    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'shenma_push';

    /**
     * 可以批量赋值的属性
     *
     * @var array
     */
    protected $fillable = [
        'url', 'type', 'status', 'msg', 'failures', 'push_at', 'included'
    ];

    /**
     * 模型的默认属性值。
     *
     * @var array
     */
    protected $attributes = [
        'status' => 0
    ];

    /**
     * 查询等待的推送
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', '=', static::STATUS_PENDING);
    }

    /**
     * 查询失败的推送
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeFailure(Builder $query): Builder
    {
        return $query->where('status', '=', static::STATUS_FAILURE);
    }

    /**
     * 是否已失败
     * @return boolean
     */
    public function getFailureAttribute(): bool
    {
        return $this->status == static::STATUS_FAILURE;
    }

    /**
     * 设置执行失败
     * @param string $msg
     * @return bool
     */
    public function setFailure(string $msg): bool
    {
        return $this->update(['status' => static::STATUS_FAILURE, 'msg' => $msg, 'failures' => $this->failures + 1, 'push_at' => $this->freshTimestamp()]);
    }

    /**
     * 设置推送成功
     * @return bool
     */
    public function setSuccess(): bool
    {
        return $this->update(['status' => static::STATUS_SUCCESS, 'msg' => 'ok', 'failures' => 0, 'push_at' => $this->freshTimestamp()]);
    }
}
