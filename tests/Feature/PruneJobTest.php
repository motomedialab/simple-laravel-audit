<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Motomedialab\SimpleLaravelAudit\Jobs\PruneAuditLogs;
use Motomedialab\SimpleLaravelAudit\Models\AuditLog;

it('will prune old audit logs', function () {

    Config::set('simple-auditor.retain_logs_for_days', 60);

    Carbon::setTestNow(now()->subDays(100));
    audit('An old item');

    Carbon::setTestNow();
    audit('A new item');

    expect(DB::table('audit_logs')->count())->toBe(2);

    dispatch_sync(new PruneAuditLogs());

    // we'd expect one to be removed and a new one recording the prune
    expect(DB::table('audit_logs')->count())->toBe(2)
        ->and(AuditLog::all()->last())
        ->message->toBe('Pruned audit logs')
        ->context->toBe(['rows_deleted' => 1]);
});
