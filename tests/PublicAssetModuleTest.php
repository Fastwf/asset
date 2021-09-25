<?php

namespace Fastwf\Tests;

use PHPUnit\Framework\TestCase;

use Fastwf\Asset\PublicAssetModule;
use Fastwf\Tests\Engine\TestingEngine;

class PublicAssetModuleTest extends TestCase {
    
    /**
     * @covers Fastwf\Asset\PublicAssetModule
     * @covers Fastwf\Asset\AssetApplication
     */
    public function testGetRoutes() {
        $engine = new TestingEngine(__DIR__ . '/configuration.ini');
        $setting = new PublicAssetModule();

        $this->assertEquals(
            1,
            \count($setting->getRoutes($engine))
        );
    }

}