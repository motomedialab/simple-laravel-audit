<?php

namespace Motomedialab\SimpleLaravelAudit\Traits;

use Illuminate\Database\Eloquent\Model;
use Motomedialab\SimpleLaravelAudit\Observers\AuditorModelObserver;

/**
 * @mixin Model
 */
trait AuditableModel
{
    public static function bootAuditableModel(): void
    {

        static::observe(config('simple-auditor.observer', AuditorModelObserver::class));
    }
}
