<?php

namespace Motomedialab\SimpleLaravelAudit\Listeners;

use Motomedialab\SimpleLaravelAudit\Contracts\IsAuditableEvent;
use Motomedialab\SimpleLaravelAudit\Facades\AuditFacade;

class AuditableEventListener
{
    public function handle(IsAuditableEvent $event): void
    {
        AuditFacade::audit($event->getAuditMessage(), $event->getAuditContext());
    }
}
