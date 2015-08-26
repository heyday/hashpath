<?php

namespace Heyday\HashPath;

class HasherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the Hasher returns the hash of a file that exists
     * @return [type] [description]
     */
    public function testGetFileHash()
    {
        $hasher = new Hasher();

        $this->assertEquals(
            'L32cPgz9R+j8qwwSRHsr8A==',
            $hasher->getFileHash(__DIR__ . '/fixtures/test.txt')
        );
    }

    /**
     * Test the Hasher throws an exception when a file isn't found
     * @throws \Heyday\HashPath\Exception
     */
    public function testGetFileHashThrows()
    {
        $hasher = new Hasher();

        try {
            $hasher->getFileHash(__DIR__ . '/non-existant-file');
            $this->fail('Expected exception not thrown');
        } catch (Exception $e) {
            // Pass
        }
    }
}