<?php

namespace Hackathon\LumenBridge;

use Hackathon\Interfaces\Application;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;

/**
 * Class LumenApplicationAdapter
 *
 * @author Roman Zaycev <box@romanzaycev.ru>
 * @package Hackathon\LumenBridge
 */
class LumenApplicationAdapter implements Application
{
    /**
     * @var bool
     */
    private $isDebug = false;

    /**
     * @var string
     */
    private $baseDir;

    /**
     * @var \Laravel\Lumen\Application
     */
    private $lumen;

    /**
     * @var PsrHttpFactory
     */
    private $psrHttpFactory;

    /**
     * @var HttpFoundationFactory
     */
    private $httpFoundationFactory;

    /**
     * LumenApplicationAdapter constructor.
     *
     * @param string $baseDir
     * @param bool $isDebug
     */
    public function __construct(string $baseDir, $isDebug = false)
    {
        $this->isDebug = $isDebug;
        $this->baseDir = $baseDir;

        (new \Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
            $this->baseDir . "/config"
        ))->bootstrap();

        /*
        |--------------------------------------------------------------------------
        | Create The Application
        |--------------------------------------------------------------------------
        |
        | Here we will load the environment and create the application instance
        | that serves as the central piece of this framework. We'll use this
        | application as an "IoC" container and router for this framework.
        |
        */

        $app = new \Laravel\Lumen\Application(
            $this->baseDir
        );

        // $app->withFacades();
        // $app->withEloquent();

        /*
        |--------------------------------------------------------------------------
        | Register Container Bindings
        |--------------------------------------------------------------------------
        |
        | Now we will register a few bindings in the service container. We will
        | register the exception handler and the console kernel. You may add
        | your own bindings here if you like or you can make another file.
        |
        */

        $app->singleton(
            \Illuminate\Contracts\Console\Kernel::class,
            \Hackathon\Application\Console\Kernel::class
        );

        $app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \Hackathon\Application\Exceptions\Handler::class
        );

        /*
        |--------------------------------------------------------------------------
        | Register Middleware
        |--------------------------------------------------------------------------
        |
        | Next, we will register the middleware with the application. These can
        | be global middleware that run before and after each request into a
        | route or middleware that'll be assigned to some specific routes.
        |
        */

        // $app->middleware([
        //    \Hackathon\Application\Http\Middleware\ExampleMiddleware::class
        // ]);

        // $app->routeMiddleware([
        //    'auth' => \Hackathon\Application\Http\Middleware\Authenticate::class,
        // ]);

        /*
        |--------------------------------------------------------------------------
        | Register Service Providers
        |--------------------------------------------------------------------------
        |
        | Here we will register all of the application's service providers which
        | are used to bind services into the container. Service providers are
        | totally optional, so you are not required to uncomment this line.
        |
        */

        // $app->register(\Hackathon\Application\Providers\AppServiceProvider::class);
        // $app->register(\Hackathon\Application\Providers\AuthServiceProvider::class);
        // $app->register(\Hackathon\Application\Providers\EventServiceProvider::class);

        /*
        |--------------------------------------------------------------------------
        | Load The Application Routes
        |--------------------------------------------------------------------------
        |
        | Next we will include the routes file so that they can all be added to
        | the application. This will provide all of the URLs the application
        | can respond to, as well as the controllers that may handle them.
        |
        */

        $app->router->group([
            'namespace' => '\Hackathon\Application\Http\Controllers',
        ], function ($router) {
            require $this->baseDir . '/routes/web.php';
        });

        $this->lumen = $app;
        $psrFactory = new Psr17Factory();
        $this->psrHttpFactory = new PsrHttpFactory(
            $psrFactory,
            $psrFactory,
            $psrFactory,
            $psrFactory
        );
        $this->httpFoundationFactory = new HttpFoundationFactory();
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $symResponse = $this
            ->lumen
            ->dispatch($this->httpFoundationFactory->createRequest($request));

        if ($symResponse instanceof \Symfony\Component\HttpFoundation\Response) {
            $resp = $this->psrHttpFactory->createResponse($symResponse);
        } else {
            $resp = new \Zend\Diactoros\Response();
            $resp->getBody()->write((string) $symResponse);
        }

        $protectedProxy = function () use($symResponse) {
            /** @var \Laravel\Lumen\Application $this */
            if (\count($this->middleware) > 0) {
                /** @noinspection PhpUndefinedMethodInspection */
                $this->callTerminableMiddleware($symResponse);
            }
        };
        $protectedProxy->call($this->lumen);

        return $resp;
    }

    /**
     * @return \Laravel\Lumen\Application
     */
    public function getLumen(): \Laravel\Lumen\Application
    {
        return $this->lumen;
    }
}