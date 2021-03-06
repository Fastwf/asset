<?php

namespace Fastwf\Asset\Handler;

use Fastwf\Asset\Utils\Mime;
use Fastwf\Api\Utils\StringUtil;
use Fastwf\Core\Router\BaseRoute;
use Fastwf\Core\Http\NotFoundException;
use Fastwf\Core\Components\RequestHandler;
use Fastwf\Core\Http\Frame\HttpStreamResponse;

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

    /**
     * The name of the application.
     * 
     * It's required to extract the parameter when the name is not null.
     *
     * @var string
     */
    protected $routeName;

    public function __construct($context, $path, $routeName = null) {
        parent::__construct($context);

        $this->path = \realpath($path);
        $this->routeName = $routeName;
    }

    /**
     * {@inheritDoc}
     */
    public function handle($request)
    {
        $fullPath = \realpath(
            \join(
                DIRECTORY_SEPARATOR,
                [
                    $this->path,
                    $request->parameters[BaseRoute::getParameterName($this->routeName, "filePath")]
                ]
            )
        );
        
        if ($fullPath && $this->canSendPath($fullPath)) {
            return new HttpStreamResponse(
                200,
                [
                    "Content-Type" => Mime::getMimeType($fullPath),
                    "Content-Length" => \filesize($fullPath),
                ],
                $this->sendFile($fullPath)
            );
        } else {
            throw new NotFoundException("No such file '{$request->path}'\n");
        }
    }

    /**
     * Verify that is possible to send the files corresponding to the path.
     *
     * @return boolean
     */
    private function canSendPath($path)
    {
        // Check that the path is a readable file and is in the exposed folder
        //  -> prevent security access with url containing %2E%2E => '..'
        return \file_exists($path)
            && \is_file($path)
            && \is_readable($path)
            && StringUtil::startsWith($path, $this->path);
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
