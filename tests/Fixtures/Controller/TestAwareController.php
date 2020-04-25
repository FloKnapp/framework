<?php

namespace Webasics\Tests\Fixtures\Controller;

use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\Controller\AbstractController;
use Webasics\Framework\DependencyInjection\ContainerAwareInterface;
use Webasics\Framework\Route\RouterAwareInterface;
use Webasics\Framework\Route\RouterAwareTrait;

/**
 * Class TestController
 * @package Webasics\Tests\Fixtures\Controller
 */
class TestAwareController extends AbstractController implements ContainerAwareInterface, RouterAwareInterface
{

    use RouterAwareTrait;

}