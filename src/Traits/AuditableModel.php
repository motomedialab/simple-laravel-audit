<?php

namespace Motomedialab\SimpleLaravelAudit\Traits;

use Illuminate\Database\Eloquent\Model;
use Motomedialab\SimpleLaravelAudit\Observers\AuditableModelObserver;

/**
 * @mixin Model
 */
trait AuditableModel
{
    public static function bootAuditableModel(): void
    {

        static::observe(config('simple-auditor.observer', AuditableModelObserver::class));
    }

    public function getExcludedFromAuditing(): array
    {
        return $this->excludedFromAuditing ?? [];
    }
}
