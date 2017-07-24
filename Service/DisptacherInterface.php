<?php
namespace Dmank\GearmanBundle\Service;

interface DisptacherInterface
{
    const PRIORITY_LOW = 0;
    const PRIORITY_NORMAL = 1;
    const PRIORITY_HIGH = 2;

    public function direct($jobName, $workLoad, $priority = DisptacherInterface::PRIORITY_LOW);
    public function inBackground($jobName, $workLoad, $priority = DisptacherInterface::PRIORITY_LOW);
}
