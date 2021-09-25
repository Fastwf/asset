<?php

namespace Fastwf\Asset;

use Fastwf\Core\Settings\RouteSettings;

/**
 * Fallback RouteSettings that serve static files from "/".
 * 
 * This module is a RouteSettings that allows to expose files provided in the /public folder.
 * 
 * The module is required as last engine settings when the application is running with the
 * built in php web server.
 */
class PublicAssetModule implements RouteSettings {

    /**
     * {@inheritDoc}
     */
    public function getRoutes($engine)
    {
        return [
            new AssetApplication(
                $engine->getServer()->get('DOCUMENT_ROOT')
                    . "/"
                    . $engine->getConfiguration()->get('server.baseUrl', ''),
                "",
                "__fastwf_asset__public"
            ),
        ];
    }

}
