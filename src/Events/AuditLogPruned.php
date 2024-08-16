<?php

namespace Motomedialab\SimpleLaravelAudit\Events;

readonly class AuditLogPruned
{
    public function __construct(public int $rowsDeleted)
    {
        //
    }
}
