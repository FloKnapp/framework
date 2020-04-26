<?php

namespace Webasics\Framework\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\DependencyInjection\ContainerAwareInterface;
use Webasics\Framework\DependencyInjection\ContainerAwareTrait;

abstract class AbstractController implements ContainerAwareInterface
{

    use ContainerAwareTrait;

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
        $body = $body->createStream($template);


        return new Response(200, [], $body);
    }

}