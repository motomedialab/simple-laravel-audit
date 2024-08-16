<?php

namespace Motomedialab\SimpleLaravelAudit\Listeners;

use Motomedialab\SimpleLaravelAudit\Events\AuditableEvent;
use Motomedialab\SimpleLaravelAudit\Facades\SimpleAudit;

class AuditableEventListener
{
    public function handle(AuditableEvent $event): void
    {
        SimpleAudit::record($event->message, $event->context);
    }

}
