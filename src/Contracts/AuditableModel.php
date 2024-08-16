<?php

namespace Motomedialab\SimpleLaravelAudit\Contracts;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Motomedialab\SimpleLaravelAudit\Observers\AuditorModelObserver;

#[ObservedBy(AuditorModelObserver::class)]
interface AuditableModel
{
    //
}
