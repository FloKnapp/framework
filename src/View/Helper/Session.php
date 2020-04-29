<?php

namespace Webasics\Framework\View\Helper;

use Webasics\Framework\DependencyInjection\ContainerAwareInterface;
use Webasics\Framework\DependencyInjection\ContainerAwareTrait;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\View\AbstractViewHelper;
use Webasics\Framework\Session\Session as OriginalSession;

/**
 * Class Session
 * @package Webasics\Framework\View\Helper
 */
class Session extends AbstractViewHelper implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    /**
     * @return OriginalSession
     * @throws NotFoundException
     */
    public function __invoke(): OriginalSession
    {
        return $this->getContainer()->get(OriginalSession::class);
    }

}