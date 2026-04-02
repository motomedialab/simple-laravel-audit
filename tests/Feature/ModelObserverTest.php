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

it('excludes defined columns from auditing on creation', function () {
    TestModel::create([
        'name' => 'Test Model',
        'email_address' => 'test@example.com'
    ]);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->message->toBe('TestModel Created')
        ->context->toHaveKey('name')
        ->context->not->toHaveKey('email_address');
});

it('excludes defined columns from auditing on update', function () {
    $model = Model::withoutEvents(fn () => TestModel::create([
        'name' => 'Test Model',
        'email_address' => 'test@example.com'
    ]));

    $model->update([
        'name' => 'Updated Model',
        'email_address' => 'updated@example.com'
    ]);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->message->toBe('TestModel Updated')
        ->context->old->toHaveKey('name')
        ->context->old->not->toHaveKey('email_address')
        ->context->new->toHaveKey('name')
        ->context->new->not->toHaveKey('email_address');
});

it('does not create audit log when only excluded columns are updated', function () {
    $model = Model::withoutEvents(fn () => TestModel::create([
        'name' => 'Test Model',
        'email_address' => 'test@example.com',
        'phone_number' => '1234567890'
    ]));

    $model->update([
        'email_address' => 'updated@example.com',
        'phone_number' => '0987654321'
    ]);

    expect(AuditLog::count())->toBe(0);
});

it('excludes multiple defined columns from auditing on update', function () {
    $model = Model::withoutEvents(fn () => TestModel::create([
        'name' => 'Test Model',
        'email_address' => 'test@example.com',
        'phone_number' => '1234567890'
    ]));

    $model->update([
        'name' => 'Updated Model',
        'email_address' => 'updated@example.com',
        'phone_number' => '0987654321'
    ]);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->message->toBe('TestModel Updated')
        ->context->old->toHaveKey('name')
        ->context->old->not->toHaveKey('email_address')
        ->context->old->not->toHaveKey('phone_number')
        ->context->new->toHaveKey('name')
        ->context->new->not->toHaveKey('email_address')
        ->context->new->not->toHaveKey('phone_number');
});

it('can use a custom context order on creation', function () {
    Config::set('simple-auditor.context_sort_order', ['id', 'class']);

    $this->withoutExceptionHandling();

    TestModel::create(['name' => 'Test Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'id' => 1,
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
            'name' => 'Test Model',
            'updated_at' => now()->toDateTimeString(),
            'created_at' => now()->toDateTimeString(),
        ]);
});

it('can handle unspecified keys in the sort order when creating', function () {
    Config::set('simple-auditor.context_sort_order', ['id', 'class', 'created_at']);

    $this->withoutExceptionHandling();

    TestModel::create(['name' => 'Test Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'id' => 1,
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
            'created_at' => now()->toDateTimeString(),
            'name' => 'Test Model',
            'updated_at' => now()->toDateTimeString(),
        ]);
});

// handling invalid arguments when creating
it('can use default order if empty array is supplied when creating', function () {
    Config::set('simple-auditor.context_sort_order', []);

    $this->withoutExceptionHandling();

    TestModel::create(['name' => 'Test Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'name' => 'Test Model',
            'updated_at' => now()->toDateTimeString(),
            'created_at' => now()->toDateTimeString(),
            'id' => 1,
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
        ]);
});

it('can use default order if an incorrect array structure is supplied when creating', function () {
    Config::set('simple-auditor.context_sort_order', ['key1' => 'value1', 'key2' => 'value2']);

    $this->withoutExceptionHandling();

    TestModel::create(['name' => 'Test Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'name' => 'Test Model',
            'updated_at' => now()->toDateTimeString(),
            'created_at' => now()->toDateTimeString(),
            'id' => 1,
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
        ]);
});

it('can use default order if a string is supplied when creating', function () {
    Config::set('simple-auditor.context_sort_order', 'a string');

    $this->withoutExceptionHandling();

    TestModel::create(['name' => 'Test Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'name' => 'Test Model',
            'updated_at' => now()->toDateTimeString(),
            'created_at' => now()->toDateTimeString(),
            'id' => 1,
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
        ]);
});

it('can use default order if an integer is supplied when creating', function () {
    Config::set('simple-auditor.context_sort_order', 0);

    $this->withoutExceptionHandling();

    TestModel::create(['name' => 'Test Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'name' => 'Test Model',
            'updated_at' => now()->toDateTimeString(),
            'created_at' => now()->toDateTimeString(),
            'id' => 1,
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
        ]);
});

it('can use default order if a boolean is supplied when creating', function () {
    Config::set('simple-auditor.context_sort_order', true);

    $this->withoutExceptionHandling();

    TestModel::create(['name' => 'Test Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'name' => 'Test Model',
            'updated_at' => now()->toDateTimeString(),
            'created_at' => now()->toDateTimeString(),
            'id' => 1,
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
        ]);
});

it('can use a reversed context order on creation', function () {
    Config::set('simple-auditor.context_sort_order', 'reverse');

    $this->withoutExceptionHandling();

    TestModel::create(['name' => 'Test Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
            'id' => 1,
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
            'name' => 'Test Model',
        ]);
});

it('can use a custom context order when updating', function () {
    Config::set('simple-auditor.context_sort_order', ['id', 'class', 'new', 'old']);

    $model = Model::withoutEvents(fn () => TestModel::create(['name' => 'Test Model']));

    $model->update(['name' => 'Updated Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'id' => 1,
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
            'new' => [
                'name' => 'Updated Model',
            ],
            'old' => [
                'name' => 'Test Model',
            ],
        ]);
});

it('can handle unspecified keys in the sort order when updating', function () {
    Config::set('simple-auditor.context_sort_order', ['id', 'class']);

    $model = Model::withoutEvents(fn () => TestModel::create(['name' => 'Test Model']));

    $model->update(['name' => 'Updated Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'id' => 1,
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
            'old' => [
                'name' => 'Test Model',
            ],
            'new' => [
                'name' => 'Updated Model',
            ],
        ]);
});

it('can handle extra undefined keys in the sort order when updating', function () {
    Config::set('simple-auditor.context_sort_order', ['id', 'class', 'new', 'old', 'foo', 'bar']);

    $model = Model::withoutEvents(fn () => TestModel::create(['name' => 'Test Model']));

    $model->update(['name' => 'Updated Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'id' => 1,
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
            'new' => [
                'name' => 'Updated Model',
            ],
            'old' => [
                'name' => 'Test Model',
            ],
        ]);
});

it('can use a reversed context order when updating', function () {
    Config::set('simple-auditor.context_sort_order', 'reverse');

    $model = Model::withoutEvents(fn () => TestModel::create(['name' => 'Test Model']));

    $model->update(['name' => 'Updated Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'id' => 1,
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
            'new' => [
                'name' => 'Updated Model',
            ],
            'old' => [
                'name' => 'Test Model',
            ],
        ]);
});

// handling invalid arguments when updating
it('can use default order if empty array is supplied when updating', function () {
    Config::set('simple-auditor.context_sort_order', []);

    $model = Model::withoutEvents(fn () => TestModel::create(['name' => 'Test Model']));

    $model->update(['name' => 'Updated Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'old' => [
                'name' => 'Test Model',
            ],
            'new' => [
                'name' => 'Updated Model',
            ],
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
            'id' => 1,
        ]);
});

it('can use default order if an incorrect array structure is supplied when updating', function () {
    Config::set('simple-auditor.context_sort_order', ['key1' => 'value1', 'key2' => 'value2']);

    $model = Model::withoutEvents(fn () => TestModel::create(['name' => 'Test Model']));

    $model->update(['name' => 'Updated Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'old' => [
                'name' => 'Test Model',
            ],
            'new' => [
                'name' => 'Updated Model',
            ],
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
            'id' => 1,
        ]);
});

it('can use default order if a string is supplied when updating', function () {
    Config::set('simple-auditor.context_sort_order', 'a string');

    $model = Model::withoutEvents(fn () => TestModel::create(['name' => 'Test Model']));

    $model->update(['name' => 'Updated Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'old' => [
                'name' => 'Test Model',
            ],
            'new' => [
                'name' => 'Updated Model',
            ],
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
            'id' => 1,
        ]);
});

it('can use default order if an integer is supplied when updating', function () {
    Config::set('simple-auditor.context_sort_order', 0);

    $model = Model::withoutEvents(fn () => TestModel::create(['name' => 'Test Model']));

    $model->update(['name' => 'Updated Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'old' => [
                'name' => 'Test Model',
            ],
            'new' => [
                'name' => 'Updated Model',
            ],
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
            'id' => 1,
        ]);
});

it('can use default order if a boolean is supplied when updating', function () {
    Config::set('simple-auditor.context_sort_order', true);

    $model = Model::withoutEvents(fn () => TestModel::create(['name' => 'Test Model']));

    $model->update(['name' => 'Updated Model']);

    expect(AuditLog::first())
        ->toBeInstanceOf(AuditLog::class)
        ->context->toBe([
            'old' => [
                'name' => 'Test Model',
            ],
            'new' => [
                'name' => 'Updated Model',
            ],
            'class' => 'Motomedialab\SimpleLaravelAudit\Tests\Stubs\TestModel',
            'id' => 1,
        ]);
});
