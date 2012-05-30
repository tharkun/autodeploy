<?php

namespace autodeploy;

abstract class task implements aggregators\runner, definitions\php\observable, definitions\task
{

    const taskStart = 'taskStart';
    const taskStop = 'taskStop';

    const stdOutStart = 'stdOutStart';
    const stdErrStart = 'stdErrStart';

    protected $runner = null;
    protected $observers = array();

    protected $client = '';
    protected $command = null;
    protected $wildcards = array();

    protected $stdOut = '';
    protected $stdErr = '';
    protected $currentOutput = null;

    /**
     * @param runner $runner
     * @param $command
     * @param array $wildcards
     */
    public function __construct(runner $runner, $command, array $wildcards)
    {
        $this
            ->setRunner($runner)
            ->setCommand($command)
            ->setWildcards($wildcards)
        ;
    }

    /**
     * @param runner $runner
     * @return task
     */
    public function setRunner(runner $runner)
    {
        $this->runner = $runner;

        return $this;
    }

    /**
     * @return runner
     */
    public function getRunner()
    {
        return $this->runner;
    }

    /**
     * @param definitions\php\observer $observer
     * @return step
     */
    public function addObserver(definitions\php\observer $observer)
    {
        $this->observers[] = $observer;

        return $this;
    }

    /**
     * @param $event
     * @return step
     */
    public function callObservers($event)
    {
        foreach ($this->observers as $observer)
        {
            $observer->handleEvent($event, $this);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param string $command
     * @return task
     */
    public function setCommand($command = null)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @param array $wildcards
     * @return task
     */
    public function setWildcards(array $wildcards)
    {
        $this->wildcards = $wildcards;

        return $this;
    }

    /**
     * @param $stdOut
     * @return task
     */
    public function setStdOut($stdOut)
    {
        $this->stdOut .= $stdOut;

        return $this;
    }

    /**
     * @return string
     */
    public function getStdOut()
    {
        return $this->stdOut;
    }

    /**
     * @param $stdErr
     * @return task
     */
    public function setStdErr($stdErr)
    {
        $this->stdErr .= $stdErr;

        return $this;
    }

    /**
     * @return string
     */
    public function getStdErr()
    {
        return $this->stdErr;
    }

    /**
     * @param $currentOutput
     * @return task
     */
    public function setCurrentOutput($currentOutput)
    {
        $this->currentOutput = $currentOutput;

        return $this;
    }

    /**
     * @return null
     */
    public function getCurrentOutput()
    {
        return $this->currentOutput;
    }

    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    /**
     * @return closure
     */
    final public function getClosureForStdout()
    {
        $self = $this;
        return function ($stdout) use ($self)
        {
            $self->setStdOut($stdout);
            $self->setCurrentOutput($stdout);
            $self->callObservers(task::stdOutStart);
        };
    }

    /**
     * @return closure
     */
    final public function getClosureForStderr()
    {
        $self = $this;
        return function ($stderr) use ($self)
        {
            $self->setStdErr($stderr);
            $self->setCurrentOutput($stderr);
            $self->callObservers(task::stdErrStart);
        };
    }


    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/
    /*****************************************************************************************************************************/


    /**
     * @return task
     */
    public function execute()
    {
        $this->client = client::FILE_SYSTEM;

        $this->callObservers(self::taskStart);

        factories\client::build($this->client, $this->getRunner(), (string) $this)->execute($this);

        $this->callObservers(self::taskStop);

        return $this;
    }

    public function getWildcardsAsString()
    {
        $self = $this;

        $anonymous = function (array $aWildCards) use ($self)
        {
            $aCommands = array();
            foreach ($aWildCards as $sWildCard)
            {
                $aCommands[] = trim( $self->getRunner()->getSystem()->cleanPath( $sWildCard ) );
            }
            return implode(' ', array_unique($aCommands));
        };

        return $anonymous(is_array($this->wildcards) ? $this->wildcards : array($this->wildcards));
    }

}
