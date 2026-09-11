<?php

namespace Verdient\Hyperf3\Job;

use Override;
use Verdient\Hyperf3\Di\Container;
use Verdient\Hyperf3\Logger\HasLogger;
use Verdient\Job\AbstractJob as JobAbstractJob;
use Verdient\Job\AdapterInterface;
use Verdient\Job\JobInterface;

use function Hyperf\Config\config;

/**
 * 抽象任务
 *
 * @author Verdient。
 */
abstract class AbstractJob extends JobAbstractJob
{
    use HasLogger;

    /**
     * @author Verdient。
     */
    #[Override]
    public function queue(): string
    {
        return config('job.default_queue');
    }

    /**
     * 获取适配器
     *
     * @author Verdient。
     */
    protected function adapter(): AdapterInterface
    {
        return Container::get(AdapterInterface::class);
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function push(): int|string|float|false
    {
        return $this->adapter()->push($this);
    }

    /**
     * 创建默认的记录器的组名集合
     *
     * @return array<int|string,string>
     * @author Verdient。
     */
    protected function groupsForCreateDefaultLogger(): array
    {
        return [static::class => JobInterface::class];
    }
}
