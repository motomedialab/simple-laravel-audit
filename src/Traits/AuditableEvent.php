<?php

namespace Motomedialab\SimpleLaravelAudit\Traits;

trait AuditableEvent
{
    public function getAuditMessage(): string
    {
        return 'Event: ' . class_basename($this);
    }

    public function getAuditContext(): array
    {
        return [];
    }
}
