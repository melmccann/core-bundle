<?php

namespace Smartbox\CoreBundle\Tests\Utils\Helper;

use Smartbox\CoreBundle\Tests\Fixtures\Entity\TestComplexEntity;
use Smartbox\CoreBundle\Utils\Helper\NamespaceResolver;

/**
 * @coversDefaultClass Smartbox\CoreBundle\Utils\Helper\NamespaceResolver
 */
class NamespaceResolverTest extends \PHPUnit\Framework\TestCase
{
    public function testResolveNamespaceForClassNameOnly()
    {
        $namespaces = [
            'Fake\Namespace',
            'Smartbox\CoreBundle\Tests\Fixtures\Entity',
            'Another\Fake\Namespace',
        ];
        $namespaceResolver = new NamespaceResolver($namespaces);

        $this->assertEquals(
            TestComplexEntity::class,
            $namespaceResolver->resolveNamespaceForClass('TestComplexEntity')
        );
    }

    public function testResolveNamespaceForClassNamespace()
    {
        $namespaces = [
            'Fake\Namespace',
            'Smartbox\CoreBundle\Tests\Fixtures\Entity',
            'Another\Fake\Namespace',
        ];
        $namespaceResolver = new NamespaceResolver($namespaces);

        $this->assertEquals(
            TestComplexEntity::class,
            $namespaceResolver->resolveNamespaceForClass(TestComplexEntity::class)
        );
    }

    public function testResolveNamespaceForNotExistingClass()
    {
        $this->expectException(\InvalidArgumentException::class);

        $namespaces = [
            'Fake\Namespace',
            'Another\Fake\Namespace',
        ];
        $namespaceResolver = new NamespaceResolver($namespaces);

        $namespaceResolver->resolveNamespaceForClass('NotExistingClass');
    }
}
