<?php

namespace Motomedialab\SimpleLaravelAudit\Events;

use Motomedialab\SimpleLaravelAudit\Contracts\IsAuditableEvent;
use Motomedialab\SimpleLaravelAudit\Traits\AuditableEvent;

class AuditLogPruned implements IsAuditableEvent
{
    use AuditableEvent;

    public function __construct(public int $rowsDeleted)
    {
        //
    }

    public function getAuditMessage(): string
    {
        return 'Pruned audit logs';
    }

    public function getAuditContext(): array
    {
        return ['rows_deleted' => $this->rowsDeleted];
    }
}
