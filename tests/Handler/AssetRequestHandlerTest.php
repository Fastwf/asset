<?php

namespace Fastwf\Tests\Handler;

use PHPUnit\Framework\TestCase;

use Fastwf\Core\Http\Frame\HttpRequest;
use Fastwf\Core\Http\NotFoundException;
use Fastwf\Tests\Handler\FileHttpOutput;
use Fastwf\Asset\Handler\AssetRequestHandler;


class AssetRequestHandlerTest extends TestCase
{

    const OUT_FILE = __DIR__ . '/out';

    /**
     * @covers Fastwf\Asset\Handler\AssetRequestHandler
     * @covers Fastwf\Asset\Utils\Mime
     */
    public function testHandle()
    {
        $handler = new AssetRequestHandler(null, __DIR__ . '/../../resources');

        $request = new HttpRequest("/index.html", "GET");
        $request->parameters = ["filePath" => "index.html"];

        $response = $handler->handle($request);

        // Test headers
        $this->assertEquals(200, $response->status);
        $this->assertEquals('text/html', $response->headers->get('Content-Type'));


        // Test response content
        $httpOutput = new FileHttpOutput(self::OUT_FILE);

        $response->send($httpOutput);

        $this->assertEquals(
            \file_get_contents(self::OUT_FILE),
            \file_get_contents(__DIR__ . '/../../resources/index.html')
        );
    }

    /**
     * @covers Fastwf\Asset\Handler\AssetRequestHandler
     * @covers Fastwf\Asset\Utils\Mime
     */
    public function testHandleWithRouteName()
    {
        $handler = new AssetRequestHandler(null, __DIR__ . '/../../resources', "assets");

        $request = new HttpRequest("/index.html", "GET");
        $request->parameters = ["assets/filePath" => "index.html"];

        $response = $handler->handle($request);

        // Test headers
        $this->assertEquals(200, $response->status);
        $this->assertEquals('text/html', $response->headers->get('Content-Type'));


        // Test response content
        $httpOutput = new FileHttpOutput(self::OUT_FILE);

        $response->send($httpOutput);

        $this->assertEquals(
            \file_get_contents(self::OUT_FILE),
            \file_get_contents(__DIR__ . '/../../resources/index.html')
        );
    }

    /**
     * @covers Fastwf\Asset\Handler\AssetRequestHandler
     * @covers Fastwf\Asset\Utils\Mime
     */
    public function testNotFound()
    {
        $this->expectException(NotFoundException::class);

        $handler = new AssetRequestHandler(null, __DIR__ . '/../../resources');

        $request = new HttpRequest("/index.xhtml", "GET");
        $request->parameters = ["filePath" => "index.xhtml"];

        $response = $handler->handle($request);
    }

    protected function tearDown(): void
    {
        if (\file_exists(self::OUT_FILE))
        {
            \unlink(self::OUT_FILE);
        }
    }

}
