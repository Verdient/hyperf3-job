<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Job;

use Hyperf\Event\Contract\ListenerInterface;
use Override;
use Psr\Log\LoggerInterface;
use Verdient\Hyperf3\Job\AbstractJob;
use Verdient\Hyperf3\Task\Logger\ConsumeLoggerInterface;
use Verdient\Hyperf3\Task\LoggerManager;
use Verdient\Job\Event\FailedToHandle;

/**
 * 处理失败事件监听器
 *
 * @author Verdient。
 */
class FailedToHandleListener implements ListenerInterface
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
            FailedToHandle::class
        ];
    }

    /**
     * 获取记录器
     *
     * @param FailedToHandle $event 事件
     *
     * @author Verdient。
     */
    protected function getLogger(FailedToHandle $event): LoggerInterface
    {
        $class = $event->dispatcher::class;

        if (!isset($this->loggers[$class])) {
            $this->loggers[$class] = LoggerManager::create($class, ConsumeLoggerInterface::class);
        }

        return $this->loggers[$class];
    }

    /**
     * @param FailedToHandle $event 事件
     *
     * @author Verdient。
     */
    #[Override]
    public function process(object $event): void
    {
        $cost = $event->endAt - $event->startAt;

        $this->getLogger($event)->info(sprintf('任务 %s 处理失败，耗时 %.4f 秒。', $event->job::class, $cost));

        if ($event->job instanceof AbstractJob) {
            $logger2 = $event->job->logger();
            $logger2->error($event->throwable);
            $logger2->info(sprintf('处理失败，耗时 %.4f 秒。', $cost));
        }
    }
}
