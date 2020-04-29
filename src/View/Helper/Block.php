<?php

namespace Webasics\Framework\View\Helper;

use Webasics\Framework\View\AbstractViewHelper;

/**
 * Class Block
 *
 * @package Webasics\Framework\View\Helper
 */
class Block extends AbstractViewHelper
{

    public static function translate($data)
    {
        return [key($data['attributes']), $data['value']];
    }

    /**
     * Block opening handler
     *
     * @param string $name
     * @param string $content
     */
    public function __invoke(string $name, string $content = '')
    {
        $this->view->getParentView()->setVariable($name, $content);
    }
}