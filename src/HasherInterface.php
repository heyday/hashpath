<?php

namespace Heyday\HashPath;

interface HasherInterface
{
    /**
     * Returns the hash of a file encoded as a string
     *
     * @param string $path - path to the file to hash
     * @return string
     * @throws Exception
     */
    public function getFileHash($path);
}