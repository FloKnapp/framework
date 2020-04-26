<?php

namespace Webasics\Tests\Fixtures\Controller;

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

    public function routerAware()
    {
        return $this->render($this->getRouter()->getRoute('test')->getAction());
    }

}