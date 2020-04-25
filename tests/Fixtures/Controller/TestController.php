<?php

namespace Webasics\Tests\Fixtures\Controller;

use Psr\Http\Message\ResponseInterface;
use Webasics\Framework\Controller\AbstractController;

/**
 * Class TestController
 * @package Webasics\Tests\Fixtures\Controller
 */
class TestController extends AbstractController
{

    /**
     * @return ResponseInterface
     */
    public function index()
    {
        return $this->render('test');
    }

    /**
     * @return ResponseInterface
     */
    public function dynamicPath()
    {
        return $this->render('dynamic');
    }

    /**
     * @return string
     */
    public function invalidResponse()
    {
        return 'invalid';
    }

}