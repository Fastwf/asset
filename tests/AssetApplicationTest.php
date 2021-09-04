<?php


namespace Fastwf\Tests;

use PHPUnit\Framework\TestCase;

use Fastwf\Asset\AssetApplication;


class AssetApplicationTest extends TestCase
{

    /**
     * @covers Fastwf\Asset\AssetApplication
     * @covers Fastwf\Asset\Handler\AssetRequestHandler
     * @covers Fastwf\Asset\Utils\Mime
     */
    public function testMatchPath()
    {
        $route = new AssetApplication(__DIR__ . '/../resources');

        $this->assertNotNull($route->match("index.html", "GET"));
    }

}
