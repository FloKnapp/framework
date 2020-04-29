<?php

namespace Webasics\Framework\View\Helper;

use Webasics\Framework\Filesystem\Exception\FileNotFoundException;
use Webasics\Framework\View\AbstractViewHelper;
use Webasics\Framework\View\Html;

/**
 * Class ParentTemplate
 *
 * @package Webasics\Framework\View\Helper
 */
class ParentTemplate extends AbstractViewHelper
{

    /**
     * @param array $arguments
     * @return array
     */
    public static function translate($arguments)
    {
        return [$arguments['value']];
    }

    /**
     * Define the parent template
     *
     * @param string $template
     *
     * @throws FileNotFoundException
     */
    public function __invoke(string $template = '')
    {
        $viewParent = new Html();
        $viewParent->setTemplate($template);
        $this->view->setParentView($viewParent);
    }
}