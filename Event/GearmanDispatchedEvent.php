<?php

namespace Dmank\GearmanBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class GearmanDispatchedEvent extends Event
{
    /**
     * @var
     */
    private $jobName;
    /**
     * @var
     */
    private $result;

    public function __construct($jobName, $result)
    {

        $this->jobName = $jobName;
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getJobName()
    {
        return $this->jobName;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}
