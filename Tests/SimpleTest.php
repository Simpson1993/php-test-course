<?php
namespace Acme\Test;

/**
 * Class SimpleTest
 * @package Acme\Test
 */
class SimpleTest extends \PHPUnit\Framework\TestCase
{
    public function testIsTrue()
    {
        $var = true;
        $this->assertTrue($var);
    }
}