<?php

namespace Fastwf\Asset;

use Fastwf\Core\Router\Route;
use Fastwf\Asset\Handler\AssetRequestHandler;


/**
 * This application is a route that match all files and return static files.
 */
class AssetApplication extends Route {
    
    /**
     * Create an instance of the static application.
     *
     * @param string $directory_path the path to the directory in the file system.
     */
    public function __construct($directory_path, $name = null) {
        parent::__construct([
            "path" => "{path:filePath}",
            "methods" => ["GET"],
            "name" => $name,
            "handler" => function ($context) use ($directory_path) {
                return new AssetRequestHandler($context, $directory_path);
            },
        ]);
    }

}
