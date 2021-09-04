<?php

namespace Fastwf\Asset\Handler;

use Fastwf\Core\Http\NotFoundException;
use Fastwf\Core\Http\Frame\HttpResponse;
use Fastwf\Core\Components\RequestHandler;
use Fastwf\Core\Http\Frame\HttpStreamResponse;

use Fastwf\Asset\Utils\Mime;


/**
 * This handler try to send a static file when exists in the filesystem else throw NotFoundException.
 */
class AssetRequestHandler extends RequestHandler {

    private const BUFFER_SIZE = 2**16;

    /**
     * The path in the file system corresponding to the static directory.
     *
     * @var string
     */
    protected $path;

    public function __construct($context, $path) {
        parent::__construct($context);

        $this->path = $path;
    }

    /**
     * {@inheritDoc}
     */
    public function handle($request)
    {
        $fullPath = \realpath(\join(DIRECTORY_SEPARATOR, [$this->path, $request->parameters["filePath"]]));
        
        if ($fullPath && \file_exists($fullPath)) {
            return new HttpStreamResponse(
                200,
                [
                    "Content-Type" => Mime::getMimeType($fullPath),
                    "Content-Length" => \filesize($fullPath)
                ],
                $this->sendFile($fullPath)
            );
        } else {
            throw new NotFoundException("No such file '{$request->path}'");
        }
    }

    /**
     * Create a generator that return chunks of BUFFER_SIZE.
     *
     * @param string $fullPath the path to the file to send
     * @return Generator the generator that send the file
     */
    private function sendFile($fullPath)
    {
        $fp = \fopen($fullPath, 'rb');

        if ($fp !== false) {
            // No error it's possible to continue
            $ok = true;

            while ($ok && !\feof($fp))
            {
                $chunk = \fread($fp, self::BUFFER_SIZE);

                if ($chunk === false)
                {
                    // Impossible to read the file
                    $ok = false;
                }
                else
                {
                    // Send the chunk to client
                    yield $chunk;
                }
            }

            // Even if an error occured, the resource must be closed
            \fclose($fp);
        }
    }

}
