<?php


namespace Fastwf\Tests;

use PHPUnit\Framework\TestCase;

use Fastwf\Asset\AssetApplication;
use Fastwf\Asset\Handler\AssetRequestHandler;


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

    /**
     * @covers Fastwf\Asset\AssetApplication
     * @covers Fastwf\Asset\Handler\AssetRequestHandler
     * @covers Fastwf\Asset\Utils\Mime
     */
    public function testMatchPathWithPrefixUrl()
    {
        $route = new AssetApplication(__DIR__ . '/../resources', 'public');

        $this->assertNotNull($route->match("public/index.html", "GET"));
    }

    /**
     * @covers Fastwf\Asset\AssetApplication
     * @covers Fastwf\Asset\Handler\AssetRequestHandler
     * @covers Fastwf\Asset\Utils\Mime
     */
    public function testCorrectHandler()
    {
        $route = new AssetApplication(__DIR__ . '/../resources');

        $this->assertTrue($route->getHandler(null) instanceof AssetRequestHandler);
    }

}
