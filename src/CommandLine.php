<?php
/**
 * This file is part of SebastianFeldmann\Cli.
 *
 * (c) Sebastian Feldmann <sf@sebastian-feldmann.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianFeldmann\Cli;

use RuntimeException;

/**
 * Class CommandLine
 *
 * @package SebastianFeldmann\Cli
 */
class CommandLine implements Command
{
    /**
     * List of system commands to execute.
     *
     * @var \SebastianFeldmann\Cli\Command[]
     */
    private $commands = [];

    /**
     * Redirect the output
     *
     * @var string
     */
    private $redirectOutput;

    /**
     * Output pipeline
     *
     * @var \SebastianFeldmann\Cli\Command[]
     */
    private $pipeline = [];

    /**
     * Redirect the stdOut.
     *
     * @param string $path
     */
    public function redirectOutputTo($path)
    {
        $this->redirectOutput = $path;
    }

    /**
     * Should the output be redirected.
     *
     * @return boolean
     */
    public function isOutputRedirected()
    {
        return !empty($this->redirectOutput);
    }

    /**
     * Redirect getter.
     *
     * @return string
     */
    public function getRedirectPath()
    {
        return $this->redirectOutput;
    }

    /**
     * Pipe the command into given command.
     *
     * @param \SebastianFeldmann\Cli\Command $cmd
     */
    public function pipeOutputTo(Command $cmd)
    {
        if (!$this->canPipe()) {
            throw new RuntimeException('Can\'t pipe output');
        }
        $this->pipeline[] = $cmd;
    }

    /**
     * Can the pipe '|' operator be used.
     *
     * @return bool
     */
    public function canPipe()
    {
        return !defined('PHP_WINDOWS_VERSION_BUILD');
    }

    /**
     * Is there a command pipeline.
     *
     * @return bool
     */
    public function isPiped()
    {
        return !empty($this->pipeline);
    }

    /**
     * Return command pipeline.
     *
     * @return string
     */
    public function getPipeline()
    {
        return $this->isPiped() ? ' | ' . implode(' | ', $this->pipeline) : '';
    }

    /**
     * Adds a cli command to list of commands to execute.
     *
     * @param \SebastianFeldmann\Cli\Command
     */
    public function addCommand(Command $cmd)
    {
        $this->commands[] = $cmd;
    }

    /**
     * Generates the system command.
     *
     * @return string
     */
    public function getCommand() : string
    {
        $amount = count($this->commands);
        if ($amount < 1) {
            throw new RuntimeException('no command to execute');
        }
        $cmd = ($amount > 1 ? '(' . implode(' && ', $this->commands) . ')' : $this->commands[0])
             . $this->getPipeline()
             . (!empty($this->redirectOutput) ? ' > ' . $this->redirectOutput : '');

        return $cmd;
    }

    /**
     * Returns the command to execute.
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->getCommand();
    }
}