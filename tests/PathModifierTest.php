<?php

namespace Heyday\HashPath;

class PathModifierTest extends \PHPUnit_Framework_TestCase
{
    public function testInlineInject()
    {
        $modifier = new PathModifier();

        $this->assertEquals(
            '/hello/world.vfea962.txt',
            $modifier->injectHash(
                '/hello/world.txt',
                'fea962'
            )
        );

        $this->assertEquals(
            '/foo/bar.v0.baz',
            $modifier->injectHash(
                '/foo/bar.baz',
                0
            )
        );
    }

    public function testParameterInject()
    {
        $modifier = new PathModifier();
        $modifier->setFormat(PathModifier::FORMAT_PARAMETER);

        $this->assertEquals(
            '/hello/world.txt?v=fea962',
            $modifier->injectHash(
                '/hello/world.txt',
                'fea962'
            )
        );

        $this->assertEquals(
            '/foo/bar.baz?v=0',
            $modifier->injectHash(
                '/foo/bar.baz',
                0
            )
        );
    }

    public function testEmptyHash()
    {
        $modifier = new PathModifier();

        $modifier->setFormat(PathModifier::FORMAT_INLINE);
        $this->assertEquals(
            '/hello/world.txt',
            $modifier->injectHash(
                '/hello/world.txt',
                ''
            )
        );

        $modifier->setFormat(PathModifier::FORMAT_PARAMETER);
        $this->assertEquals(
            'world.txt',
            $modifier->injectHash(
                'world.txt',
                ''
            )
        );
    }

    public function testStripNonUnreserved()
    {
        $modifier = new PathModifier();

        $this->assertEquals(
            '/hello+world.vL32cPgz9Rj8qwwSRHsr8A.txt',
            $modifier->injectHash(
                '/hello+world.txt',
                'L32cPgz9R+j8qwwSRHsr8A=='
            )
        );
    }
}