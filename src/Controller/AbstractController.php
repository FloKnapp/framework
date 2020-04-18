<?php

namespace Webasics\Framework\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractController
{

    /** @var RequestInterface */
    private RequestInterface $request;

    /** @var ResponseInterface */
    private ResponseInterface $response;

    /**
     * @param string $template
     * @param array  $parameters
     * @return ResponseInterface
     */
    public function render(string $template, array $parameters = [])
    {
        $body = new Psr17Factory();
        $body = $body->createStream('test');


        return new Response(200, [], $body);
    }

}