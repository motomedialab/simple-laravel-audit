<?php

use Motomedialab\SimpleLaravelAudit\Models\AuditLog;
use Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestEvent;

it('listens for an auditable event', function () {

    // dispatch our test event
    event(new TestEvent());

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->message->toBe('Event: TestEvent');

});
