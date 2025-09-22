<?php

namespace TidewaysInvalidateLog;

use Shopware\Core\Framework\Adapter\Cache\InvalidatorStorage\AbstractInvalidatorStorage;
use Psr\Log\LoggerInterface;

class LoggingInvalidatorStorage extends AbstractInvalidatorStorage
{
        public function __construct(
                private AbstractInvalidatorStorage $inner,
                private LoggerInterface $logger,
        ) {}

    public function store(array $tags): void
    {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 12);
            $idx = str_contains($backtrace[1]['file'], 'CacheInvalidationSubscriber') ? 8 : 1;
            $this->logger->error(
                'InvalidatorStorage::store',
                [
                    'tags' =>  implode(", ", $tags),
                    'source' => $backtrace[$idx]['file'] .  ':' . $backtrace[$idx]['line'],
                    'transaction' => method_exists('Tideways\Profiler', 'getTransactionName')
                        ? Profiler::getTransactionName()
                        : null,
                ],
            );
            $this->inner->store($tags);
    }

    public function loadAndDelete(): array
    {
            return $this->inner->loadAndDelete();
    }
}
