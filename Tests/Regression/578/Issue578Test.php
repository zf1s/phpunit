<?php
class Issue578Test extends PHPUnit_Framework_TestCase
{
    public function testNoticesDoublePrintStackTrace()
    {
        $this->iniSet('error_reporting', E_ALL | E_NOTICE);
        if (PHP_VERSION_ID < 80000) {
            trigger_error('Stack Trace Test Notice', E_NOTICE);
        } else {
            trigger_error('Invalid error type specified', E_USER_NOTICE);
        }
    }

    public function testWarningsDoublePrintStackTrace()
    {
        $this->iniSet('error_reporting', E_ALL | E_NOTICE);
        if (PHP_VERSION_ID < 80000) {
            trigger_error('Stack Trace Test Notice', E_WARNING);
        } else {
            trigger_error('Invalid error type specified', E_USER_WARNING);
        }
    }

    public function testUnexpectedExceptionsPrintsCorrectly()
    {
        throw new Exception('Double printed exception');
    }
}
