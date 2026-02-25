<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Job\Annotation;

use Hyperf\Di\MetadataCollector;

/**
 * 异步任务收集器
 *
 * @method static ?Job get(string $key, $default = null)
 * @method static array<class-string,Job> list()
 *
 * @author Verdient。
 */
class JobCollector extends MetadataCollector
{
    /**
     * @inheritdoc
     *
     * @author Verdient。
     */
    protected static array $container = [];

    /**
     * 收集类
     *
     * @param class-string $className 类名
     * @param Job $annotation 注解
     *
     * @author Verdient。
     */
    public static function collectClass(string $className, Job $annotation): void
    {
        static::$container[$className] = $annotation;
    }
}
