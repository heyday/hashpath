<?php

namespace Heyday\HashPath;

/**
 * Path Modifier
 *
 * Inject hash strings into the URL paths to force user-agents to fetch updated
 * versions of files that have changed instead of serving them from a cache.
 */
class PathModifier
{
    /**
     * Format: Hash injected into filename before the extension
     *
     * eg. /my/app/main.js => /my/app/main.v123456abcdef.js
     */
    const FORMAT_INLINE = 'inline';

    /**
     * Format: Hash appended to URL as a query string
     * Useful when rewrite rules are not supported by the web server, but not ideal for production use.
     *
     * eg. /my/app/main.js => /my/app/main.js?v=123456abcdef
     */
    const FORMAT_PARAMETER = 'parameter';

    /**
     * Method to use when injecting a hashes into paths
     * @var string - one of the FORMAT_* constants
     */
    protected $format = self::FORMAT_INLINE;

    /**
     * Set the format to use when injecting a hash into a pathh
     *
     * @param int $format - one of the FORMAT_* constants
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * Inject a hash string into a path using the configured format
     *
     * @param string $path
     * @param string $hash
     * @return string
     */
    public function injectHash($path, $hash)
    {
        $hash = $this->stripPathUnsafe($hash);

        if ($hash === null || $hash === '') {
            // Where the hash is empty, return the original path as there's no use injecting it
            return $path;
        }

        return $this->formatPath($path, $hash, $this->format);
    }

    /**
     * Do the actual re-jigging of the path to inject/append the hash string
     *
     * This assumes that the hash is already URL safe.
     *
     * @param string $path
     * @param string $hash
     * @param string $format
     * @return string
     * @throws Exception
     */
    protected function formatPath($path, $hash, $format)
    {
        switch ($format) {
            case self::FORMAT_INLINE:
                $pathInfo = pathinfo($path);

                return $this->joinPaths(array(
                    $pathInfo['dirname'],
                    sprintf(
                        '%s.v%s.%s',
                        $pathInfo['filename'],
                        $hash,
                        $pathInfo['extension']
                    )
                ));

            case self::FORMAT_PARAMETER:
                return $path . "?v=$hash";

            default:
                throw new Exception("Unable to format using unknown format '$format'");
        }
    }

    /**
     * Strip characters from a string that aren't allowed in the path component of a URL
     *
     * This is fixed rather than flexible as the characters allowed here need to
     * match the regex used for URL rewriting in the web server. More characters are
     * technically allowed in paths, but this list keeps it simple and paths tidy.
     *
     * @param string $hash
     * @return string
     */
    protected function stripPathUnsafe($hash)
    {
        // Remove characters other than those specified as unreserved in RFC3986
        return preg_replace('/[^A-Za-z0-9\-\.\_\~]/', '', $hash);
    }

    /**
     * Join path components with mixed leading and trailing slashes
     *
     * @param string[] $paths
     * @return string
     */
    protected function joinPaths($paths)
    {
        $paths = array_filter($paths);
        return preg_replace(',/+,','/', join('/', $paths));
    }
}
