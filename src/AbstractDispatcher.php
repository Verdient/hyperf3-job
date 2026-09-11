<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Job;

use Override;
use Verdient\Job\AbstractDispatcher as JobAbstractDispatcher;
use Verdient\Job\AdapterInterface;

use function Hyperf\Config\config;
use function Hyperf\Support\make;

/**
 * 抽象调度器
 *
 * @author Verdient。
 */
abstract class AbstractDispatcher extends JobAbstractDispatcher
{
    /**
     * 队列名称
     *
     * @author Verdient。
     */
    protected string $queue;

    /**
     * @author Verdient。
     */
    public function __construct()
    {
        $this->queue = config('job.default_queue');
        parent::__construct(make(AdapterInterface::class));
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function queue(): string
    {
        return $this->queue;
    }
}
