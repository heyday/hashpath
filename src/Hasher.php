<?php

namespace Heyday\HashPath;

/**
 * Hasher
 *
 * Get the hash of a file on disk. The hash is encoded in as few characters as
 * possible to not bloat URLs unnecessarily.
 */
class Hasher implements HasherInterface
{
    /**
     * Get the MD5 hash of a file encoded as base64
     *
     * The raw checksum value is base64 encoded rather than hex as it normally
     * might be. This results in hash strings than are 24 characters instead of 32.
     *
     * @param string $path - absolute path to the target file
     * @return string
     * @throws Exception
     */
    public function getFileHash($path)
    {
        if (file_exists($path)) {
            return $this->encodeRawHash(
                md5_file($path, true)
            );
        } else {
            throw new Exception("No such file '$path'");
        }
    }

    /**
     * Encode binary hash value as base64
     *
     * @param $binary
     * @return string
     */
    protected function encodeRawHash($binary)
    {
        echo strlen(bin2hex($binary)), "\n", strlen(base64_encode($binary));
        return base64_encode($binary);
    }
}
