<?php

namespace Webasics\Framework\Http\Adapter;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\Http\Exception\HttpClientAdapterException;

/**
 * Interface AdapterInterface
 * @package Webasics\Framework\Http
 */
interface AdapterInterface
{

    /**
     * @return bool
     */
    public function isSupported(): bool;

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     *
     * @throws HttpClientAdapterException
     */
    public function send(RequestInterface $request): ResponseInterface;

}