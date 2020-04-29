<?php

namespace Webasics\Framework\View;

use Webasics\Framework\Filesystem\Exception\FileNotFoundException;
use Webasics\Framework\View\Exception\TemplateException;
use Webasics\Framework\View\Exception\ViewHelperException;

/**
 * Class AbstractViewHelper
 * @package Webasics\Framework\View
 * @method __invoke()
 */
abstract class AbstractViewHelper
{

    /** @var AbstractView */
    protected ?AbstractView $view = null;
    
    /**
     * @param array $data
     *
     * @return array
     */
    public static function translate(array $data) {
        // gets overwritten
        return [];
    }

    /**
     * Render a view with given template and variables
     *
     * @param  string $template
     * @param  array  $variables
     *
     * @return string
     *
     * @throws FileNotFoundException
     * @throws TemplateException
     * @throws ViewHelperException
     */
    protected function renderView($template = '', array $variables = []) :string
    {
        $templatePath = __DIR__ . '/../View/Helper';

        return (new Html())->setTemplate($templatePath . $template)->setVariables($variables)->render();
    }

    /**
     * @param AbstractView $view
     */
    public function setView(AbstractView $view): void
    {
        $this->view = $view;
    }

    /**
     * @return AbstractView
     */
    public function getView(): AbstractView
    {
        return $this->view;
    }

}