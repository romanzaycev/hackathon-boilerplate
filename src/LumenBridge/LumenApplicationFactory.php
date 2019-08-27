<?php

namespace Hackathon\LumenBridge;

use Hackathon\Interfaces\Application;
use Hackathon\Interfaces\ApplicationFactory;

/**
 * Class LumenApplicationFactory
 *
 * @author Roman Zaycev <box@romanzaycev.ru>
 * @package Hackathon\LumenBridge
 */
class LumenApplicationFactory implements ApplicationFactory
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
     * @var Application|null
     */
    private $app;

    /**
     * @inheritDoc
     */
    public function getApplication(string $baseDir, bool $isDebug): Application
    {
        if (!$this->app) {
            $this->baseDir = $baseDir;
            $this->isDebug = $isDebug;
            $this->app = new LumenApplicationAdapter(
                $this->baseDir,
                $this->isDebug
            );
        }

        return $this->app;
    }
}