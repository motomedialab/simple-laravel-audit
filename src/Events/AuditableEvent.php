<?php

namespace Motomedialab\SimpleLaravelAudit\Events;

readonly class AuditableEvent
{
    public function __construct(public string $message, public array $context = [])
    {
        //
    }
}
