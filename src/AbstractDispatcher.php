<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Job;

use Verdient\Job\AbstractDispatcher as JobAbstractDispatcher;
use Verdient\Job\AdapterInterface;

/**
 * 抽象调度器
 *
 * @author Verdient。
 */
abstract class AbstractDispatcher extends JobAbstractDispatcher
{
    /**
     * @author Verdient。
     */
    public function __construct()
    {
        parent::__construct($this->adapter());
    }

    /**
     * 获取适配器
     *
     * @author Verdient。
     */
    abstract protected function adapter(): AdapterInterface;
}
