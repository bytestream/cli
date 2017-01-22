<?php
/**
 * This file is part of SebastianFeldmann\Cli.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianFeldmann\Cli\Command;

/**
 * Class ExecutableTest
 *
 * @package SebastianFeldmann\Cli
 */
class ExecutableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests Executable::addArgument
     */
    public function testAddArgumentPlain()
    {
        $cmd = new Executable('foo');
        $cmd->addArgument('bar');

        $this->assertEquals('foo \'bar\'', (string) $cmd, 'argument should be added');
    }

    /**
     * Tests Executable::silence
     */
    public function testSilence()
    {
        $cmd = new Executable('foo');
        $cmd->silence(true);

        $this->assertEquals('foo 2> /dev/null', (string) $cmd, 'command should be silenced');
    }

    /**
     * Tests Executable::addArgument
     */
    public function testAddArgumentArray()
    {
        $cmd = new Executable('foo');
        $cmd->addArgument(array('bar', 'baz'));

        $this->assertEquals('foo \'bar\' \'baz\'', (string) $cmd, 'arguments should be added');
    }

    /**
     * Tests Executable::addOption
     */
    public function testAddOptionArray()
    {
        $cmd = new Executable('foo');
        $cmd->addOption('-bar', array('fiz', 'baz'));

        $this->assertEquals('foo -bar \'fiz\' \'baz\'', (string) $cmd, 'arguments should be added');
    }

    /**
     * Tests Executable::addOptionIfNotEmpty
     */
    public function testAddOptionIfEmpty()
    {
        $cmd = new Executable('foo');
        $cmd->addOptionIfNotEmpty('-bar', '', false);

        $this->assertEquals('foo', (string) $cmd, 'option should not be added');

        $cmd->addOptionIfNotEmpty('-bar', 'fiz', false);

        $this->assertEquals('foo -bar', (string) $cmd, 'option should be added');
    }

    /**
     * Tests Executable::addOptionIfNotEmpty
     */
    public function testAddOptionIfEmptyAsValue()
    {
        $cmd = new Executable('foo');
        $cmd->addOptionIfNotEmpty('-bar', '');

        $this->assertEquals('foo', (string) $cmd, 'option should not be added');

        $cmd->addOptionIfNotEmpty('-bar', 'fiz');

        $this->assertEquals('foo -bar=\'fiz\'', (string) $cmd, 'option should be added');
    }
}