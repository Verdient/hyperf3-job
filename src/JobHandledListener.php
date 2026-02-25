<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Job;

use Hyperf\Event\Contract\ListenerInterface;
use Override;
use Psr\Log\LoggerInterface;
use Verdient\Hyperf3\Task\Logger\ConsumeLoggerInterface;
use Verdient\Hyperf3\Task\LoggerManager;
use Verdient\Job\Event\Handled;

/**
 * 任务处理事件监听器
 *
 * @author Verdient。
 */
class JobHandledListener implements ListenerInterface
{
    /**
     * 缓存的日志记录器
     *
     * @author Verdient。
     */
    protected array $loggers = [];

    /**
     * @author Verdient。
     */
    #[Override]
    public function listen(): array
    {
        return [
            Handled::class
        ];
    }

    /**
     * 获取记录器
     *
     * @param Handled $event 事件
     *
     * @author Verdient。
     */
    protected function getLogger(Handled $event): LoggerInterface
    {
        $class = $event->dispatcher::class;

        if (!isset($this->loggers[$class])) {
            $this->loggers[$class] = LoggerManager::create($class, ConsumeLoggerInterface::class);
        }

        return $this->loggers[$class];
    }

    /**
     * @param Handled $event 事件
     *
     * @author Verdient。
     */
    #[Override]
    public function process(object $event): void
    {
        $cost = $event->endAt - $event->startAt;

        $this->getLogger($event)
            ->info(sprintf('任务 %s 处理成功，耗时 %.4f 秒。', $event->job::class, $cost));

        if ($event->job instanceof AbstractJob) {
            $event->job->logger()->info(sprintf('处理成功，耗时 %.4f 秒。', $cost));
        }
    }
}
