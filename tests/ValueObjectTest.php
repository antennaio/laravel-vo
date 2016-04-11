<?php

namespace Antennaio\VO\Test;

use InvalidArgumentException;

class ValueObjectTest extends TestCase
{
    public function testCreate()
    {
        $color = new HexColor('#fff');

        $this->assertInstanceOf('Antennaio\VO\ValueObject', $color);
        $this->assertEquals($color, '#fff');
    }

    public function testException()
    {
        $this->expectException(InvalidArgumentException::class);

        $domain = new HexColor('#xxx');
    }
}
