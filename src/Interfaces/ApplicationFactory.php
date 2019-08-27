<?php

namespace Hackathon\Interfaces;

/**
 * Interface ApplicationFactory
 * 
 * @author Roman Zaycev <box@romanzaycev.ru>
 * @package Hackathon\Interfaces
 */
interface ApplicationFactory
{
    /**
     * Application factory method
     *
     * @param string $baseDir
     * @param bool $isDebug
     * @return Application
     */
    public function getApplication(string $baseDir, bool $isDebug): Application;
}