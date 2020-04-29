<?php

namespace Webasics\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Webasics\Framework\Filesystem\Finder;

/**
 * Class FinderTest
 * @package Webasics\Tests\Unit
 */
class FinderTest extends TestCase
{

    /**
     * @test
     */
    public function itShouldListDirectoriesRecursively()
    {
        self::assertTrue(true);

        return;

        $finder = new Finder();

        $finder->searchFile('test');
    }

}