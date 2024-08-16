<?php

namespace Motomedialab\SimpleLaravelAudit\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public const UPDATED_AT = null;

    protected $guarded = [];

    public function getTable()
    {
        return config('simple-auditor.table_name', 'audit_logs');
    }

    protected function casts(): array
    {
        return [
            'context' => ['array'],
        ];
    }
}
