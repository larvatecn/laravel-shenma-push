<?php
/**
 * This is NOT a freeware, use is subject to license terms
 * @copyright Copyright (c) 2010-2099 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larva.com.cn/
 */

namespace Larva\Shenma\Push\Commands;

use Illuminate\Console\Command;
use Larva\Shenma\Push\Jobs\PushJob;
use Larva\Shenma\Push\ShenmaPushModel;

/**
 * Push
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class Push extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shenma:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'shenma push';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = ShenmaPushModel::pending()->count();
        $bar = $this->output->createProgressBar($count);
        ShenmaPushModel::pending()->orderBy('push_at', 'asc')->chunk(100, function ($results) use ($bar) {
            /** @var ShenmaPushModel $push */
            foreach ($results as $push) {
                PushJob::dispatch($push);
                $bar->advance();
            }
        });
        $bar->finish();
    }
}
