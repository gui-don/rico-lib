<?php

declare(strict_types=1);

namespace Rico\Test;

class RicoTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Do a standard test on $utilFile:$functionToTest with $value and $expected. Handles the TypeError exception automatically.
     *
     * @param string $utilFile
     * @param string $functionToTest
     * @param array $argsValue
     * @param mixed $expected
     */
    protected function standardTest(string $utilFile, string $functionToTest, array $argsValue, $expected)
    {
        $this->callbackTest($utilFile, $functionToTest, $argsValue, $expected, function () use ($argsValue, $expected, $utilFile, $functionToTest) {
            $this->assertSame($expected, $utilFile::$functionToTest(...$argsValue));
        });
    }

    /**
     * Do a $function test on $utilFile:$functionToTest with $value and $expected. Handles the TypeError exception automatically.
     *
     * @param string $utilFile
     * @param string $functionToTest
     * @param array $argsValue
     * @param mixed $expected
     * @param \Closure $function
     */
    protected function callbackTest(string $utilFile, string $functionToTest, array $argsValue, $expected, \Closure $function)
    {
        if ($expected !== null) {
            $function();
        } else {
            $this->setExpectedException('TypeError');
            $utilFile::$functionToTest(...$argsValue);
        }
    }
}
