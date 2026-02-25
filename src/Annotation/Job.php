<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Job\Annotation;

use Verdient\Hyperf3\Job\AbstractDispatcher;
use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;
use Override;
use TypeError;

/**
 * 异步任务
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Job extends AbstractAnnotation
{
    /**
     * @author Verdient。
     */
    #[Override]
    public function collectClass(string $className): void
    {
        if (!is_subclass_of($className, AbstractDispatcher::class)) {
            throw new TypeError('The class ' . $className . ' with #[Job] must implement ' . AbstractDispatcher::class . '.');
        }

        JobCollector::collectClass($className, $this);
    }
}
