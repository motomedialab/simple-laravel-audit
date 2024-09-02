<?php

namespace Motomedialab\SimpleLaravelAudit\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;
use Motomedialab\SimpleLaravelAudit\Traits\AuditableModel;

class TestModel extends Model
{
    use AuditableModel;

    protected $guarded = [];

    protected array $excludedFromAuditing = [
        'email_address',
        'phone_number',
    ];
}
