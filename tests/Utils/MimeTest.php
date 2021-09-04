<?php

namespace Fastwf\Tests\Utils;

use Fastwf\Asset\Utils\Mime;
use PHPUnit\Framework\TestCase;


class MimeTest extends TestCase
{
    
    public static function setUpBeforeClass(): void
    {
        if (\file_exists(Mime::CACHE_PATH))
        {
            // Be sure to read the original file before the cache file
            \unlink(Mime::CACHE_PATH);
        }
    }

    /**
     * @covers Fastwf\Asset\Utils\Mime
     */
    public function testLoadMimeTypes()
    {
        $mimeTypes = Mime::loadMimeTypes();

        $this->assertEquals('text/css', $mimeTypes['css']);
        $this->assertEquals('text/plain', $mimeTypes['log']);
        $this->assertEquals('image/jpeg', $mimeTypes['jpe']);
    }

    /**
     * @covers Fastwf\Asset\Utils\Mime
     */
    public function testGetMimeTypeCommon()
    {
        $this->assertEquals('text/css', Mime::getMimeType('/app/static/style.css'));
    }

    /**
     * @covers Fastwf\Asset\Utils\Mime
     */
    public function testGetMimeTypeCommonUsingCache()
    {
        Mime::clear();

        $this->assertEquals('text/css', Mime::getMimeType('/app/static/style.CSS'));
    }

    /**
     * @covers Fastwf\Asset\Utils\Mime
     */
    public function testGetMimeTypeFallback()
    {
        $this->assertEquals('text/plain', Mime::getMimeType(__DIR__ . '/mime.cfg'));
    }

    /**
     * @covers Fastwf\Asset\Utils\Mime
     */
    public function testGetMimeTypeFallbackFailed()
    {
        $this->assertNull(Mime::getMimeType(__DIR__ . '/app/static/file.sdx'));
    }

    /**
     * @covers Fastwf\Asset\Utils\Mime
     */
    public function testGetMimeTypeDefault()
    {
        $this->assertEquals(
            'application/octet-stream',
            Mime::getMimeType(__DIR__ . '/app/static/file.sdx', null, 'application/octet-stream')
        );
    }

}
