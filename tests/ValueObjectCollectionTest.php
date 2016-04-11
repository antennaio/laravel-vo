<?php

namespace Antennaio\VO\Test;

use InvalidArgumentException;

class ValueObjectCollectionTest extends TestCase
{
    public function testCreateFromArray()
    {
        $colors = new HexColorCollection(['#FFF', '#000000', '#f15e52']);

        $this->assertInstanceOf('Antennaio\VO\ValueObjectCollection', $colors);
        $this->assertInstanceOf('Illuminate\Support\Collection', $colors->getCollection());

        $this->assertEquals('#FFF, #000000, #f15e52', $colors);
    }

    public function testCreateFromString()
    {
        $colors = new HexColorCollection('#FFF,   #000000,#f15e52');

        $this->assertInstanceOf('Antennaio\VO\ValueObjectCollection', $colors);
        $this->assertInstanceOf('Illuminate\Support\Collection', $colors->getCollection());

        $this->assertEquals('#FFF, #000000, #f15e52', $colors);
    }

    public function testException()
    {
        $this->expectException(InvalidArgumentException::class);

        $domains = new HexColorCollection(['#FFF', '#000000', '#xxx']);
    }

    public function testUnique()
    {
        $colors = new HexColorCollection(['#000', '#000', '#000']);

        $this->assertEquals('#000', $colors);
    }

    public function testCount()
    {
        $colors = new HexColorCollection(['#FFF', '#000']);

        $this->assertEquals(2, $colors->count());
    }

    public function testIterate()
    {
        $colors = new HexColorCollection(['#FFF', '#000']);

        $trimmed = '';

        foreach ($colors as $color) {
            $trimmed .= ltrim($color, '#');
        }

        $this->assertEquals('FFF000', $trimmed);
    }
}
