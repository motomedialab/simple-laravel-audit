<?php

namespace Motomedialab\SimpleLaravelAudit\Contracts;

interface IsAuditableEvent
{
    public function getAuditMessage(): string;

    public function getAuditContext(): array;
}
