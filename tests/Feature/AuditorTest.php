<?php

use Motomedialab\SimpleLaravelAudit\Contracts\AuditorContract;
use Motomedialab\SimpleLaravelAudit\Contracts\FetchesIpAddress;
use Motomedialab\SimpleLaravelAudit\Contracts\FetchesUserId;
use Motomedialab\SimpleLaravelAudit\Events\AuditableEvent;
use Motomedialab\SimpleLaravelAudit\Facades\SimpleAudit;
use Motomedialab\SimpleLaravelAudit\Models\AuditLog;

it('can load an auditor instance', function () {
    expect(app('simple-auditor'))
        ->toBeInstanceOf(AuditorContract::class)
        ->and(app(AuditorContract::class))
        ->toBeInstanceOf(AuditorContract::class);
});

it('can create an audit log via the facade', function () {

    $this->mock(FetchesIpAddress::class)
        ->shouldReceive('__invoke')->once()->andReturn('testIpAddress');

    $this->mock(FetchesUserId::class)
        ->shouldReceive('__invoke')->once()->andReturn(12);

    $log = SimpleAudit::record('This is a test audit log', ['foo' => 'bar']);

    expect($log)->toBeInstanceOf(config('simple-auditor.model'))
        ->message->toBe('This is a test audit log')
        ->context->toBe(['foo' => 'bar'])
        ->ip_address->toBe('testIpAddress')
        ->user_id->toBe(12);
});

it('can create an audit log via the helper', function () {
    $this->mock(FetchesIpAddress::class)->shouldReceive('__invoke')->once()->andReturnNull();
    $this->mock(FetchesUserId::class)->shouldReceive('__invoke')->once()->andReturnNull();

    $log = audit('Testing', ['blah' => 'bloo']);

    expect($log)->toBeInstanceOf(config('simple-auditor.model'))
        ->message->toBe('Testing')
        ->context->toBe(['blah' => 'bloo'])
        ->ip_address->toBeNull()
        ->user_id->toBeNull();
});

it('can create an audit log via the event', function () {
    event(new AuditableEvent('Testing', ['blah' => 'bloo']));

    expect(AuditLog::first())->toBeInstanceOf(config('simple-auditor.model'))
        ->message->toBe('Testing')
        ->context->toBe(['blah' => 'bloo']);
});
