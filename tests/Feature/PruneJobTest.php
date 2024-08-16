<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Motomedialab\SimpleLaravelAudit\Jobs\PruneOldAuditLogs;

it('will prune old audit logs', function () {

    Config::set('simple-auditor.retain_logs_for_days', 60);

    Carbon::setTestNow(now()->subDays(100));
    audit('An old item');

    Carbon::setTestNow();
    audit('A new item');

    expect(DB::table('audit_logs')->count())->toBe(2);

    dispatch_sync(new PruneOldAuditLogs());

    expect(DB::table('audit_logs')->count())->toBe(1);
});
