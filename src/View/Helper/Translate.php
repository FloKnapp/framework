<?php

namespace Webasics\Framework\View\Helper;

use Webasics\Framework\DependencyInjection\ContainerAwareInterface;
use Webasics\Framework\DependencyInjection\ContainerAwareTrait;
use Webasics\Framework\Exceptions\NotFoundException;
use Webasics\Framework\i18n\Translator;
use Webasics\Framework\View\AbstractViewHelper;

/**
 * Class Translate
 * @package Webasics\Framework\View\Helper
 */
class Translate extends AbstractViewHelper implements ContainerAwareInterface
{

    use ContainerAwareTrait;

    /**
     * @param string $key
     * @param array  $vars
     *
     * @return mixed
     *
     * @throws NotFoundException
     */
    public function __invoke(string $key, $vars = [])
    {
        /** @var Translator $translator */
        $translator = $this->getContainer()->get(Translator::class);
        return $translator->translate($key, $vars);
    }

}