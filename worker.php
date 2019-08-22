<?php

/**
 * Worker script
 *
 * Must to be running under Roadrunner
 */

\ini_set('display_errors', 'stderr');
require __DIR__ . "/vendor/autoload.php";

$relay = new Spiral\Goridge\StreamRelay(STDIN, STDOUT);
$psr7 = new Spiral\RoadRunner\PSR7Client(new Spiral\RoadRunner\Worker($relay));

while ($req = $psr7->acceptRequest()) {
    try {
        $resp = new \Zend\Diactoros\Response();
        $resp->getBody()->write("hello world, path: " . \htmlspecialchars($req->getRequestTarget()));
        $psr7->respond($resp);
    } catch (\Throwable $e) {
        $psr7->getWorker()->error((string)$e);
    }
}
