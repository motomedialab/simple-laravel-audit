<?php

namespace Motomedialab\SimpleLaravelAudit\Facades;

use Illuminate\Support\Facades\Facade;
use Motomedialab\SimpleLaravelAudit\Models\AuditLog;

/**
 * @method static AuditLog record(string $message, array $context = [])
 * @method static AuditLog audit(string $message, array $context = [])
 */
class AuditFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'simple-auditor';
    }
}
