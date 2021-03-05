<?php
/**
 * @copyright Copyright (c) 2018 Jinan Larva Information Technology Co., Ltd.
 * @link http://www.larvacent.com/
 * @license http://www.larvacent.com/license/
 */

namespace Larva\Shenma\Push\Commands;

use Illuminate\Console\Command;
use Larva\Shenma\Push\Jobs\PushJob;
use Larva\Shenma\Push\ShenmaPushModel;

/**
 * PushRetry
 *
 * @author Tongle Xu <xutongle@gmail.com>
 */
class PushRetry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shenma:push-retry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'shenma push retry.';

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
        $count = ShenmaPushModel::failure()->count();
        $bar = $this->output->createProgressBar($count);
        ShenmaPushModel::failure()->orderBy('push_at', 'asc')->chunk(100, function ($results) use ($bar) {
            /** @var ShenmaPushModel $push*/
            foreach ($results as $push) {
                PushJob::dispatch($push);
                $bar->advance();
            }
        });
        $bar->finish();
    }
}
