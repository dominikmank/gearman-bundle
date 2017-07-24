<?php

namespace Dmank\GearmanBundle\Event;


final class GearmanEvent
{
    CONST BEFORE_SYNC_EXECUTE = 'before.sync.execute';
    CONST AFTER_SYNC_EXECUTE = 'after.sync.execute';
    CONST BEFORE_ASYNC_EXECUTE = 'before.async.execute';
    CONST AFTER_ASYNC_EXECUTE = 'after.async.execute';
}
