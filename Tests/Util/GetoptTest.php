<?php
/*
 * This file is part of PHPUnit.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Util_GetoptTest extends PHPUnit_Framework_TestCase
{
    public function testItIncludeTheLongOptionsAfterTheArgument()
    {
        $args = array(
            'command',
            'myArgument',
            '--colors',
        );
        $actual = PHPUnit_Util_Getopt::getopt($args, '', array('colors=='));

        $expected = array(
            array(
                array(
                    '--colors',
                    null,
                ),
            ),
            array(
                'myArgument',
            ),
        );

        $this->assertEquals($expected, $actual);
    }

    public function testItIncludeTheShortOptionsAfterTheArgument()
    {
        $args = array(
            'command',
            'myArgument',
            '-v',
        );
        $actual = PHPUnit_Util_Getopt::getopt($args, 'v');

        $expected = array(
            array(
                array(
                    'v',
                    null,
                ),
            ),
            array(
                'myArgument',
            ),
        );

        $this->assertEquals($expected, $actual);
    }

    public function testShortOptionUnrecognizedException()
    {
        $args = array(
            'command',
            'myArgument',
            '-v',
        );

        $this->setExpectedException('PHPUnit_Framework_Exception', 'unrecognized option -- v');

        PHPUnit_Util_Getopt::getopt($args, '');
    }

    public function testShortOptionRequiresAnArgumentException()
    {
        $args = array(
            'command',
            'myArgument',
            '-f',
        );

        $this->setExpectedException('PHPUnit_Framework_Exception', 'option requires an argument -- f');

        PHPUnit_Util_Getopt::getopt($args, 'f:');
    }

    public function testShortOptionHandleAnOptionalValue()
    {
        $args = array(
            'command',
            'myArgument',
            '-f',
        );
        $actual   = PHPUnit_Util_Getopt::getopt($args, 'f::');
        $expected = array(
            array(
                array(
                    'f',
                    null,
                ),
            ),
            array(
                'myArgument',
            ),
        );
        $this->assertEquals($expected, $actual);
    }

    public function testLongOptionIsAmbiguousException()
    {
        $args = array(
            'command',
            '--col',
        );

        $this->setExpectedException('PHPUnit_Framework_Exception', 'option --col is ambiguous');

        PHPUnit_Util_Getopt::getopt($args, '', array('columns', 'colors'));
    }

    public function testLongOptionUnrecognizedException()
    {
        // the exception 'unrecognized option --option' is not thrown
        // if the there are not defined extended options
        $args = array(
            'command',
            '--foo',
        );

        $this->setExpectedException('PHPUnit_Framework_Exception', 'unrecognized option --foo');

        PHPUnit_Util_Getopt::getopt($args, '', array('colors'));
    }

    public function testLongOptionRequiresAnArgumentException()
    {
        $args = array(
            'command',
            '--foo',
        );

        $this->setExpectedException('PHPUnit_Framework_Exception', 'option --foo requires an argument');

        PHPUnit_Util_Getopt::getopt($args, '', array('foo='));
    }

    public function testLongOptionDoesNotAllowAnArgumentException()
    {
        $args = array(
            'command',
            '--foo=bar',
        );

        $this->setExpectedException('PHPUnit_Framework_Exception', "option --foo doesn't allow an argument");

        PHPUnit_Util_Getopt::getopt($args, '', array('foo'));
    }

    public function testItHandlesLongParametesWithValues()
    {
        $command = 'command parameter-0 --exec parameter-1 --conf config.xml --optn parameter-2 --optn=content-of-o parameter-n';
        $args    = explode(' ', $command);
        unset($args[0]);
        $expected = array(
            array(
                array('--exec', null),
                array('--conf', 'config.xml'),
                array('--optn', null),
                array('--optn', 'content-of-o'),
            ),
            array(
                'parameter-0',
                'parameter-1',
                'parameter-2',
                'parameter-n',
            ),
        );
        $actual = PHPUnit_Util_Getopt::getopt($args, '', array('exec', 'conf=', 'optn=='));
        $this->assertEquals($expected, $actual);
    }

    public function testItHandlesShortParametesWithValues()
    {
        $command = 'command parameter-0 -x parameter-1 -c config.xml -o parameter-2 -ocontent-of-o parameter-n';
        $args    = explode(' ', $command);
        unset($args[0]);
        $expected = array(
            array(
                array('x', null),
                array('c', 'config.xml'),
                array('o', null),
                array('o', 'content-of-o'),
            ),
            array(
                'parameter-0',
                'parameter-1',
                'parameter-2',
                'parameter-n',
            ),
        );
        $actual = PHPUnit_Util_Getopt::getopt($args, 'xc:o::');
        $this->assertEquals($expected, $actual);
    }
}
