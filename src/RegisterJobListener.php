<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Job;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Framework\Event\BeforeMainServerStart;
use Hyperf\Server\Event\MainCoroutineServerStart;
use Override;
use Psr\Container\ContainerInterface;
use Verdient\Hyperf3\Task\Event\EventDispatcher;
use Verdient\Hyperf3\Task\TaskManager;

use function Hyperf\Support\class_basename;
use function Hyperf\Support\make;

/**
 * 注册Job监听器
 *
 * @author Verdient。
 */
class RegisterJobListener implements ListenerInterface
{
    /**
     * @param ContainerInterface $container 容器
     *
     * @author Verdient。
     */
    public function __construct(protected ContainerInterface $container) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function listen(): array
    {
        return [
            BeforeMainServerStart::class,
            MainCoroutineServerStart::class,
        ];
    }

    /**
     * @param BeforeMainServerStart|MainCoroutineServerStart $event 事件
     *
     * @author Verdient。
     */
    #[Override]
    public function process(object $event): void
    {
        $config = $this->container->get(ConfigInterface::class)->get('job');

        if ($config['enable'] !== true) {
            return;
        }

        $eventDispatcher = new EventDispatcher;

        foreach (JobManager::all() as $class => $identifier) {

            $dispatcher = make($class);

            $dispatcher->setEventDispatcher($eventDispatcher);

            $configuration = TaskManager::parse($class);

            if ($configuration->identifier === null) {

                if ($identifier) {
                    $configuration->identifier = $identifier;
                } else {
                    $classBasename = class_basename($class);

                    if (str_ends_with($classBasename, 'Job') && $classBasename !== 'Job') {
                        $classBasename = substr($classBasename, 0, -strlen('Job'));
                    }

                    $configuration->identifier = 'Job.' . $classBasename;
                }
            }

            TaskManager::add(
                $dispatcher,
                $configuration,
            );
        }
    }
}
