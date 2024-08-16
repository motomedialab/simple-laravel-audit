<?php

namespace Motomedialab\SimpleLaravelAudit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Motomedialab\SimpleLaravelAudit\Models\AuditLog record(string $message, array $context = [])
 * @method static \Motomedialab\SimpleLaravelAudit\Models\AuditLog audit(string $message, array $context = [])
 */
class AuditFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'simple-auditor';
    }
}
