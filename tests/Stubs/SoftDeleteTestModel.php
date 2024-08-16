<?php

namespace Motomedialab\SimpleLaravelAudit\Tests\Stubs;

use Illuminate\Database\Eloquent\SoftDeletes;

class SoftDeleteTestModel extends TestModel
{
    use SoftDeletes;

    protected $table = 'test_models';
}
