<?php

namespace Fastwf\Asset\Utils;


/**
 * Utility class that help to detect the mime type of given filename.
 */
class Mime {

    private const ORIGINAL_PATH = __DIR__ . '/mime-types.txt';
    const CACHE_PATH = __DIR__ . '/mime-types.bin';

    private static $map = null;

    /**
     * Get the mime type from the filename.
     *
     * @param string $filename the path of the file
     * @param string $fallback the function name to call when the mime type is not found (can be null)
     * @param mixed $default the default value to use if mime type is not found and fallback failed
     * @return mixed the mime type found or null
     */
    public static function getMimeType($filename, $fallback="\mime_content_type", $default=null)
    {
        $map = self::getMimeTypes();
        
        $extension = \pathinfo($filename, PATHINFO_EXTENSION);

        $mimeType = null;
        // Try to find the mime type from the loaded file reference
        if ($extension)
        {
            // Ignore case to search the extension
            $extension = \strtolower($extension);

            if (\array_key_exists($extension, $map))
            {
                $mimeType = $map[$extension];
            }
        }

        // When the mime type is not found:
        // - use fallback function
        // - use default value (same if fallback function failed)
        if ($mimeType === null)
        {
            if ($fallback !== null)
            {
                try {
                    $mimeType = \call_user_func($fallback, $filename);

                    if ($mimeType === false)
                    {
                        // Fallback failed to define the type
                        $mimeType = null;
                    }
                } catch (\Throwable $th) {
                    // ignore
                }
            }

            // No mime type found, use default when it's provided
            if ($mimeType === null && $default !== null)
            {
                $mimeType = $default;
            }
        }

        return $mimeType;
    }

    /**
     * Get mime type from file system or from memory if already loaded.
     *
     * @return array the map of mime types.
     */
    private static function getMimeTypes()
    {
        if (self::$map === null)
        {
            self::$map = self::loadMimeTypesCached();
        }
        
        return self::$map;
    }

    /**
     * Load the mime type map from the cache or from the original file.
     *
     * @return array the map of mime types
     */
    private static function loadMimeTypesCached()
    {
        if (\file_exists(self::CACHE_PATH))
        {
            $map = \unserialize(
                \file_get_contents(self::CACHE_PATH)
            );
        }
        else
        {
            $map = self::loadMimeTypes();

            \file_put_contents(
                self::CACHE_PATH,
                \serialize($map)
            );
        }

        return $map;
    }

    /**
     * Allows to load from a file the list of mime types associated to the extension and return a map of
     * extension => mime type.
     *
     * @return array the map of extension / mime type
     */
    public static function loadMimeTypes()
    {
        $mimeTypes = [];

        // Load from the file
        $fp = \fopen(self::ORIGINAL_PATH, 'r');

        if ($fp !== false)
        {
            // Read the file line by line and try to extract mime-type and extension
            while (!\feof($fp))
            {
                $line = \fgets($fp);

                if ($line === false)
                {
                    // Failed to read the line
                    break;
                }
                else
                {
                    // Process the line because it's not empty
                    $items = \explode("\t", $line, 2);

                    // The file is considered as valid format, so there are always 2 items
                    $mimeType = $items[0];
                    $extensions = \trim($items[1]);

                    // Register the  mime type according to the extension
                    foreach (\explode(" ", $extensions) as $extension)
                    {
                        $mimeTypes[$extension] = $mimeType;
                    }
                }
            }

            // Finally close the file
            \fclose($fp);
        }

        return $mimeTypes;
    }

    /**
     * Clear ram mime type loaded to force to reload from cache or original file.
     *
     * @return void
     */
    public static function clear()
    {
        self::$map = null;
    }

}
