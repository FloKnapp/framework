<?php

namespace Webasics\Framework\View;

/**
 * Interface ViewInterface
 * @package Webasics\Framework\View
 */
interface ViewInterface
{

    /**
     * @param string $template
     * @return static
     */
    public function setTemplate(string $template);

    /**
     * @return string
     */
    public function render(): string;

}