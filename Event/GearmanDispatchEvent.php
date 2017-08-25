<?php
namespace Dmank\GearmanBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class GearmanDispatchEvent extends Event
{
    /**
     * @var string
     */
    private $jobname;
    /**
     * @var array
     */
    private $workload;
    /**
     * @var
     */
    private $priority;

    public function __construct($jobname, $workload, $priority)
    {

        $this->jobname = $jobname;
        $this->workload = $workload;
        $this->priority = $priority;
    }

    /**
     * @return string
     */
    public function getJobname()
    {
        return $this->jobname;
    }

    /**
     * @return array
     */
    public function getWorkload()
    {
        return $this->workload;
    }

    /**
     * @param array $workload
     */
    public function setWorkload(array $workload)
    {
        $this->workload = $workload;
    }

    /**
     * @return mixed
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }
}
