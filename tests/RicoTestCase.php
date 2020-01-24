<?php

declare(strict_types=1);

namespace Rico\Test;

class RicoTestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * Do a standard static test on $utilFile:$functionToTest with $value and $expected. Handles the TypeError exception automatically.
     *
     * @param string $utilFile
     * @param string $functionToTest
     * @param array  $argsValue
     * @param mixed  $expected
     */
    protected function standardStaticTest(string $utilFile, string $functionToTest, array $argsValue, $expected)
    {
        $this->callbackStandardStaticTest($utilFile, $functionToTest, $argsValue, $expected, function () use ($argsValue, $expected, $utilFile, $functionToTest) {
            $this->assertSame($expected, $utilFile::$functionToTest(...$argsValue));
        });
    }

    /**
     * Do a $function test on $utilFile:$functionToTest with $value and $expected. Handles the TypeError exception automatically.
     *
     * @param string   $utilFile
     * @param string   $functionToTest
     * @param array    $argsValue
     * @param mixed    $expected
     * @param \Closure $function
     */
    protected function callbackStandardStaticTest(string $utilFile, string $functionToTest, array $argsValue, $expected, \Closure $function)
    {
        if ($expected !== null) {
            $function();
        } else {
            $this->expectException('TypeError');
            $utilFile::$functionToTest(...$argsValue);
        }
    }

    /**
     * Do a standard test on $utilFile:$functionToTest with $value and $expected. Handles the TypeError exception automatically.
     *
     * @param mixed  $utilObject
     * @param string $functionToTest
     * @param array  $argsValue
     * @param mixed  $expected
     */
    protected function standardTest($utilObject, string $functionToTest, array $argsValue, $expected)
    {
        $this->callbackStandardTest($utilObject, $functionToTest, $argsValue, $expected, function () use ($argsValue, $expected, $utilObject, $functionToTest) {
            $this->assertSame($expected, call_user_func_array([$utilObject, $functionToTest], $argsValue));
        });
    }

    /**
     * Do a $function test on $utilFile:$functionToTest with $value and $expected. Handles the TypeError exception automatically.
     *
     * @param mixed    $utilObject
     * @param string   $functionToTest
     * @param array    $argsValue
     * @param mixed    $expected
     * @param \Closure $function
     */
    protected function callbackStandardTest($utilObject, string $functionToTest, array $argsValue, $expected, \Closure $function)
    {
        if ($expected !== null) {
            $function();
        } else {
            $this->expectException('TypeError');
            call_user_func_array([$utilObject, $functionToTest], $argsValue);
        }
    }
}
