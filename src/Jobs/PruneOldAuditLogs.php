<?php

namespace Motomedialab\SimpleLaravelAudit\Jobs;

use Illuminate\Database\Eloquent\Model;
use Motomedialab\SimpleLaravelAudit\Events\AuditLogPruned;

class PruneOldAuditLogs
{
    /**
     * Delete audit logs that are older than the retention period.
     */
    public function handle(): void
    {
        /** @var Model $model */
        $model = config('simple-auditor.model');
        $retention = config('simple-auditor.retain_logs_for_days');

        $count = $model::query()
            ->where('created_at', '<=', now()->subDays($retention))
            ->delete();

        if ($count > 0) {
            event(new AuditLogPruned($count));
        }
    }
}
