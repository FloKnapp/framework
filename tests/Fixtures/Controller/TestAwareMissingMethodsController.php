<?php

namespace Webasics\Tests\Fixtures\Controller;

use Webasics\Framework\Controller\AbstractController;
use Webasics\Framework\Route\RouterAwareInterface;

/**
 * Class TestAwareMissingMethodsController
 * @package Webasics\Tests\Fixtures\Controller
 */
class TestAwareMissingMethodsController extends AbstractController implements RouterAwareInterface
{

    public function index()
    {
        return $this->render('test');
    }

}