<?php

/**
 * Worker script
 *
 * Must to be running under Roadrunner
 */

use Hackathon\LumenBridge\LumenApplicationFactory;

\ini_set('display_errors', 'stderr');
require __DIR__ . "/vendor/autoload.php";

$relay = new Spiral\Goridge\StreamRelay(STDIN, STDOUT);
$psr7 = new Spiral\RoadRunner\PSR7Client(new Spiral\RoadRunner\Worker($relay));

(Dotenv\Dotenv::create(__DIR__ . "/config"))->load();

$applicationFactory = new LumenApplicationFactory();
$app = $applicationFactory->getApplication(
    __DIR__,
    \getenv("ENVIRONMENT") === "development"
);

while ($req = $psr7->acceptRequest()) {
    try {
        $psr7->respond($app->handle($req));
    } catch (\Throwable $e) {
        $psr7->getWorker()->error((string)$e);
    }
}
