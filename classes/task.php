<?php

namespace autodeploy;

final class task implements aggregators\runner, definitions\php\observable//, definitions\task
{

    const taskStart = 'taskStart';
    const taskStop = 'taskStop';

    const stdOutStart = 'stdOutStart';
    const stdErrStart = 'stdErrStart';

    protected $runner = null;
    protected $observers = array();

    protected $command = null;

    protected $stdOut = '';
    protected $stdErr = '';
    protected $currentOutput = null;

    /**
     * @param runner $runner
     * @param $command
     */
    public function __construct(runner $runner, command $command)
    {
        $this
            ->setRunner($runner)
            ->setCommand($command)
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
     * @param string $command
     * @return task
     */
    public function setCommand($command = null)
    {
        $this->command = $command;

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
     * @return task
     */
    public function execute()
    {
        $this->callObservers(self::taskStart);

        $executeTask = true;

        if ($this->getRunner()->getQuiet() !== true && $this->getRunner()->getPromptBeforeExecution() === true)
        {
            $executeTask = 'y' === $this->getRunner()->prompt("Please confirm you want to execute command [y] : ");
        }

        $this->getRunner()->setCommands(array(array((string) $this, $executeTask)), true);

        if ($executeTask)
        {
            $self = $this;

            $engine = new engine( (string) $this );
            $engine->execute(
                function ($stdout) use ($self)
                {
                    $self->setStdOut($stdout);
                    $self->setCurrentOutput($stdout);
                    $self->callObservers(task::stdOutStart);
                },
                function ($stderr) use ($self)
                {
                    $self->setStdErr($stderr);
                    $self->setCurrentOutput($stderr);
                    $self->callObservers(task::stdErrStart);
                }
            );
        }

        $this->callObservers(self::taskStop);

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->command;
    }
}
