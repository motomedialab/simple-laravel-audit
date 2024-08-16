<?php

use Illuminate\Database\Eloquent\Model;
use Motomedialab\SimpleLaravelAudit\Models\AuditLog;
use Motomedialab\SimpleLaravelAudit\Tests\Stubs\SoftDeleteTestModel;
use Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel;

beforeEach(fn () => $this->loadMigrationsFrom(__DIR__ . '/../migrations'));

it('can observe a model for creation', function () {
    $this->withoutExceptionHandling();

    TestModel::create(['name' => 'Test Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->message->toBe('TestModel Created');
});

it('can observe a model for updates', function () {
    $model = Model::withoutEvents(fn () => TestModel::create(['name' => 'Test Model']));

    $model->update(['name' => 'Updated Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->message->toBe('TestModel Updated')
        ->context->old->name->toBe('Test Model')
        ->context->new->name->toBe('Updated Model');
});

it('can observe a model for deletion', function () {
    $model = Model::withoutEvents(fn () => TestModel::create(['name' => 'Test Model']));

    $model->delete();

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->message->toBe('TestModel Deleted');
});

it('can observe a model for soft deletion', function () {
    $model = Model::withoutEvents(fn () => SoftDeleteTestModel::create(['name' => 'Test Model']));

    $model->delete();

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->message->toBe('SoftDeleteTestModel Soft Deleted');
});

it('can observe a model for forced deletion', function () {
    $model = TestModel::withoutEvents(fn () => SoftDeleteTestModel::create(['name' => 'Test Model']));

    $model->forceDelete();

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->message->toBe('SoftDeleteTestModel Force Deleted');
});
