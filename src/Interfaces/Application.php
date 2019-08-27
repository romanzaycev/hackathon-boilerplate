<?php

namespace Hackathon\Interfaces;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface Application
 *
 * @author Roman Zaycev <box@romanzaycev.ru>
 * @package Hackathon\Interfaces
 */
interface Application
{
    /**
     * Handle HTTP request
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface;
}