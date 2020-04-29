<?php

namespace Webasics\Framework\View\Helper;

use Webasics\Framework\View\AbstractViewHelper;

/**
 * Class AssetList
 *
 * @category ViewHelper
 * @package  Webasics\Framework\View\Helper
 */
class AssetList extends AbstractViewHelper
{

    /**
     * Render a asset list by type
     *
     * @param string         $type     The asset type
     * @param bool           $optimize If all assets should be concatenated
     *                                 within style tag in head
     *
     * @return string
     */
    public function __invoke($type, $optimize = false)
    {
        $result  = '';
        $pattern = '';

        switch ($type) {
            case 'js':
                $pattern = '<script src="%s" type="module"></script>';
                break;
            case 'css':
                $pattern = '<link rel="stylesheet" type="text/css" href="%s">';
                break;
        }

        /** @var array $files */
        $files = $this->view->getVariable('assets' . ucfirst($type));
        if (empty($files)) {
            return '';
        }
        if ($type === 'css' && $optimize) {
            $result  = '<style type="text/css">';
            $result .= $this->collectAssetsContent($files, $type);
            $result .= '</style>';
            return $result;
        }
        if ($type === 'js' && $optimize) {
            $result  = '<script>';
            $result .= $this->collectAssetsContent($files, $type);
            $result .= '</script>';
            return $result;
        }
        foreach ($files AS $file) {
            $result .= sprintf($pattern, $file) . "\n";
        }
        return $result;
    }
    /**
     * Collect all assets content for concatenation
     *
     * @param array  $files The asset files
     * @param string $type  The assets type
     *
     * @return string
     */
    private function collectAssetsContent(array $files, string $type): string
    {
        $docRoot = dirname(__DIR__, 3) . '/public';
        $content  = '';
        $contents = [];
        foreach ($files as $file) {
            if (file_exists($docRoot . $file)) {
                $contents[] = file_get_contents($docRoot . $file);
            }
        }
        if ($type === 'css') {
            $content = str_replace(
                ["\n", "\t", "  ", ": ", " {", "{ ", " }", ";}"],
                ["", "", "", ":", "{", "{", "}", "}"],
                implode('', $contents)
            );
        }
        return $content;
    }
}