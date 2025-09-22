<?php

namespace TidewaysInvalidateLog;

use Shopware\Core\Framework\Adapter\Cache\InvalidateCacheEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;
use Tideways\Profiler;

class InvalidateListener implements EventSubscriberInterface
{
    public function __construct(
            private LoggerInterface $logger,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            InvalidateCacheEvent::class => 'onCacheInvalidated',
        ];
    }

    public function onCacheInvalidated(InvalidateCacheEvent $event): void
    {
            $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
            $idx = 8;
            $counts = ['product-listing' => 0, 'product' => 0, 'product-stream' => 0, 'system' => 0, 'template' => 0];

            foreach ($event->getKeys() as $key) {
                    $dashes = substr_count($key, '-');
                    if ($dashes === 0) continue;
                    $parts = array_slice(explode('-', $key), 0, $dashes);
                    $counts[implode("-", $parts)]++;
            }


            $this->logger->error(
                    'InvalidateCacheEvent',
                    [
                            'tag_count' => count($event->getKeys()),
                            'source' => $backtrace[$idx]['file'] .  ':' . $backtrace[$idx]['line'],
                            'counts' => $counts,
                            'transaction' => method_exists('Tideways\Profiler', 'getTransactionName')
                                 ? Profiler::getTransactionName()
                                 : null,
                    ],
            );
    }
}