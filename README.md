# Tideways Shopware Invalidation Logger

This plugin adds support to logging invalidations of the HTTP cache
in your application. This gives you an idea how often cache items are invalidated
every minute, and where these invalidations are coming from.

It works with Shopware 6.7 delayed invalidation as well by logging
both the delayed and the actual invalidations and their source.

> [!WARNING]  
> This plugin is experimental at the moment as we seek out production testers.

```
composer require tideways/shopware-invalidation-logger-plugin
php bin/console plugin:refresh
php bin/console plugin:install TidewaysInvalidateLog
php bin/console plugin:activate TidewaysInvalidateLog
```

It logs into the default Monolog channel two messages, one starting
with "InvalidatorStorage::store" the other with "InvalidateCacheEvent".

Example:

```
[2025-09-22T20:05:48.540614+00:00] app.ERROR: InvalidatorStorage::store {"tags":"product-listing-0196a9ee38a6722893d2d12482bb8258, product-listing-0196a9f7567672f3ac6d3a15b065af67, product-listing-0196a9f75696713cbb8fb2f0d92c38ad, product-listing-0196a9f75696713cbb8fb2f0d96ece4b, product-listing-0196a9f7569973a093dbd28796c4ddcc, product-listing-0196a9f7569973a093dbd28798ca5b59, product-0196a9ffe00a7338bc3f491e645f56f3, product-stream-0196a9ffe51670cb9666cc22d37babc5","source":"/var/www/shopware-6.7/vendor/shopware/core/Content/Product/DataAbstractionLayer/ProductIndexer.php:241"} []
[2025-09-22T20:06:01.045659+00:00] app.ERROR: InvalidateCacheEvent {"tags":"product-stream-0196a9ffe51670cb9666cc22d37babc5, product-listing-0196a9ee38a6722893d2d12482bb8258, product-listing-0196a9f7567672f3ac6d3a15b065af67, product-listing-0196a9f75696713cbb8fb2f0d92c38ad, product-listing-0196a9f75696713cbb8fb2f0d96ece4b, product-listing-0196a9f7569973a093dbd28796c4ddcc, product-listing-0196a9f7569973a093dbd28798ca5b59, product-0196a9ffe00a7338bc3f491e645f56f3","tag_count":8,"source":"/var/www/shopware-6.7/vendor/shopware/core/Framework/MessageQueue/ScheduledTask/ScheduledTaskHandler.php:46","counts":{"product-listing":6,"product":1,"product-stream":1,"system":0,"template":0}} []
```