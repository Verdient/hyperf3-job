<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\Job;

use Verdient\Hyperf3\Job\Annotation\JobCollector;
use Verdient\Job\JobInterface;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for job.',
                    'source' => dirname(__DIR__) . '/publish/job.php',
                    'destination' => constant('BASE_PATH') . '/config/autoload/job.php',
                ]
            ],
            'listeners' => [
                RegisterLoggerRuleListener::class => 101,
                RegisterJobListener::class => 100
            ],
            'annotations' => [
                'scan' => [
                    'collectors' => [
                        JobCollector::class
                    ]
                ],
            ],
            'logger' => [
                JobInterface::class => fn($name) => Utils::generateLoggerConfig($name)
            ]
        ];
    }
}
