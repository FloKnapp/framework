<?php

namespace Webasics\Framework\Filesystem;

/**
 * Class File
 * @package Webasics\Framework\Filesystem
 */
class Finder
{

    /** @var string */
    private string $baseDir;

    /**
     * File constructor.
     * @param string $baseDir
     */
    public function __construct(string $baseDir = '')
    {
        $this->baseDir = $baseDir;
    }

    public function searchFile($filename)
    {
        $everything = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(__DIR__ . '/../../'));

        var_dump($everything->callGetChildren()->getChildren());
    }

    /**
     * @param string[] $files
     */
    private function fileExistsMulti(array $files = [])
    {
        $everything = new \RecursiveDirectoryIterator(__DIR__ . '/../..');

        var_dump($everything->getChildren() );

        foreach ($everything->getChildren() as $item) {
            var_dump($item);
        }
    }

}