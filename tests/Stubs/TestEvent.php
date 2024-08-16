<?php

namespace Motomedialab\SimpleLaravelAudit\Tests\Stubs;

use Motomedialab\SimpleLaravelAudit\Contracts\IsAuditableEvent;
use Motomedialab\SimpleLaravelAudit\Traits\AuditableEvent;

class TestEvent implements IsAuditableEvent
{
    use AuditableEvent;
}
