<?php

namespace Webasics\Framework\View\Helper;

use Webasics\Framework\View\AbstractViewHelper;
use Webasics\Framework\Filesystem\Exception\FileNotFoundException;
use Webasics\Framework\View\Exception\TemplateException;
use Webasics\Framework\View\Html;

/**
 * Class RenderView | RenderView.php
 * @package Webasics\Framework\View\Helper
 */
class RenderView extends AbstractViewHelper
{
    /**
     * Render a subview
     *
     * @param string $template
     * @param array  $variables
     * @return string
     *
     * @throws FileNotFoundException
     * @throws TemplateException
     */
    public function __invoke(string $template = '', array $variables = [])
    {
        $subview = new Html();

        $subview->setTemplate($template);
        $subview->setVariables($variables);

        return $subview->render();
    }
}