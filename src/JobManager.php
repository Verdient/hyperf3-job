<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Job;

use Verdient\Hyperf3\Job\Annotation\JobCollector;
use Verdient\Job\DispatcherInterface;

/**
 * 任务管理器
 *
 * @author Verdient。
 */
class JobManager
{
    /**
     * 任务集合
     *
     * @author Verdient。
     */
    protected static ?array $jobs = null;

    /**
     * 初始化任务
     *
     * @author Verdient。
     */
    protected static function initJobs(): void
    {
        if (static::$jobs === null) {
            static::$jobs = [];

            foreach (array_keys(JobCollector::list()) as $class) {
                static::$jobs[$class] = null;
            }
        }
    }

    /**
     * 添加任务
     *
     * @param class-string<DispatcherInterface> $class 类名
     * @param ?string $identifier 标识符
     *
     * @author Verdient。
     */
    public static function add(
        string $class,
        ?string $identifier = null
    ): void {
        static::initjobs();
        static::$jobs[$class] = $identifier;
    }

    /**
     * 获取任务集合
     *
     * @return array<class-string<DispatcherInterface>,?string>
     * @author Verdient。
     */
    public static function all(): array
    {
        static::initjobs();

        return static::$jobs;
    }
}
