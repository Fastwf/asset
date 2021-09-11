<?php

namespace Fastwf\Asset;

use Fastwf\Core\Router\Route;
use Fastwf\Core\Utils\StringUtil;

use Fastwf\Asset\Handler\AssetRequestHandler;


/**
 * This application is a route that match all files and return static files.
 */
class AssetApplication extends Route {
    
    /**
     * Create an instance of the static application.
     *
     * @param string $directoryPath the path to the directory in the file system.
     * @param string $prefixUrl the prefix of the route.
     * @param string|null $name the name of the route
     */
    public function __construct($directoryPath, $prefixUrl = "", $name = null) {
        parent::__construct([
            "path" => self::getSafePrefixUrl($prefixUrl) . "{path:filePath}",
            "methods" => ["GET"],
            "name" => $name,
            "handler" => function ($context) use ($directoryPath) {
                return new AssetRequestHandler($context, $directoryPath);
            },
        ]);
    }

    /**
     * Generate a safe prefix url for asset route.
     *
     * @param string $prefix the prefix used for the asset route.
     * @return string the url ready to concat with path parameter.
     */
    private static function getSafePrefixUrl($prefix)
    {
        return $prefix === "" || StringUtil::endsWith($prefix, "/")
            ? $prefix
            : "$prefix/";
    }

}
