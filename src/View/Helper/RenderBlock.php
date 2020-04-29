<?php

namespace Webasics\Framework\View\Helper;

use Webasics\Framework\View\AbstractViewHelper;

/**
 * Class RenderBlock
 *
 * @package Webasics\Framework\View\Helper
 */
class RenderBlock extends AbstractViewHelper
{
    public static function translate(array $data)
    {
        return [key($data['attributes']), $data['attributes']['default'] ?? ''];
    }

    /**
     * Render a defined block from variable
     *
     * @param string         $block
     * @param string         $defaultValue
     *
     * @return array|string
     */
    public function __invoke(string $block, string $defaultValue = '')
    {
        if($this->view->getVariable($block) === '') {
            return $defaultValue;
        }

        return trim($this->view->getVariable($block));
    }
}