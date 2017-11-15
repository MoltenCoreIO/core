<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ApiPlatform\Core\Tests\Metadata\Resource;

use ApiPlatform\Core\Metadata\Resource\ResourceMetadata;
use PHPUnit\Framework\TestCase;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class ResourceMetadataTest extends TestCase
{
    public function testValueObject()
    {
        $metadata = new ResourceMetadata('shortName', 'desc', 'http://example.com/foo', ['iop1' => ['foo' => 'a'], 'iop2' => ['bar' => 'b']], ['cop1' => ['foo' => 'c'], 'cop2' => ['bar' => 'd']], ['baz' => 'bar'], ['foo' => 'graphql']);
        $this->assertSame('shortName', $metadata->getShortName());
        $this->assertSame('desc', $metadata->getDescription());
        $this->assertSame('http://example.com/foo', $metadata->getIri());
        $this->assertSame(['iop1' => ['foo' => 'a'], 'iop2' => ['bar' => 'b']], $metadata->getItemOperations());
        $this->assertSame('a', $metadata->getItemOperationAttribute('iop1', 'foo', 'z', false));
        $this->assertSame('bar', $metadata->getItemOperationAttribute('iop1', 'baz', 'z', true));
        $this->assertSame('bar', $metadata->getItemOperationAttribute(null, 'baz', 'z', true));
        $this->assertSame('z', $metadata->getItemOperationAttribute('iop1', 'notExist', 'z', true));
        $this->assertSame('z', $metadata->getItemOperationAttribute('notExist', 'notExist', 'z', true));
        $this->assertSame(['cop1' => ['foo' => 'c'], 'cop2' => ['bar' => 'd']], $metadata->getCollectionOperations());
        $this->assertSame('c', $metadata->getCollectionOperationAttribute('cop1', 'foo', 'z', false));
        $this->assertSame('bar', $metadata->getCollectionOperationAttribute('cop1', 'baz', 'z', true));
        $this->assertSame('bar', $metadata->getCollectionOperationAttribute(null, 'baz', 'z', true));
        $this->assertSame('z', $metadata->getCollectionOperationAttribute('cop1', 'notExist', 'z', true));
        $this->assertSame('z', $metadata->getCollectionOperationAttribute('notExist', 'notExist', 'z', true));
        $this->assertSame(['baz' => 'bar'], $metadata->getAttributes());
        $this->assertSame('bar', $metadata->getAttribute('baz'));
        $this->assertSame('z', $metadata->getAttribute('notExist', 'z'));
        $this->assertSame('graphql', $metadata->getGraphqlQueryAttribute('foo'));
        $this->assertSame('bar', $metadata->getGraphqlQueryAttribute('baz', null, true));
        $this->assertSame('hey', $metadata->getGraphqlQueryAttribute('notExist', 'hey', true));
    }

    /**
     * @dataProvider getWithMethods
     */
    public function testWithMethods(string $name, $value)
    {
        $metadata = new ResourceMetadata();
        $newMetadata = call_user_func([$metadata, "with$name"], $value);
        $this->assertNotSame($metadata, $newMetadata);
        $this->assertSame($value, call_user_func([$newMetadata, "get$name"]));
    }

    public function getWithMethods(): array
    {
        return [
            ['ShortName', 'shortName'],
            ['Description', 'description'],
            ['Iri', 'iri'],
            ['ItemOperations', ['a' => ['b' => 'c']]],
            ['CollectionOperations', ['a' => ['b' => 'c']]],
            ['Attributes', ['a' => ['b' => 'c']]],
            ['GraphqlQuery', ['a' => ['b' => 'c']]],
        ];
    }
}
