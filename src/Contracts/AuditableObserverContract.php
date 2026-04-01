<?php

namespace Motomedialab\SimpleLaravelAudit\Contracts;

use Illuminate\Database\Eloquent\Model;

interface AuditableObserverContract
{
    public function created(Model $model);
    public function updated(Model $model);
    public function deleted(Model $model);
}