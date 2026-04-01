<?php

namespace Motomedialab\SimpleLaravelAudit\Traits;

use Illuminate\Database\Eloquent\Model;
use Motomedialab\SimpleLaravelAudit\Contracts\AuditableObserverContract;

/**
 * @mixin Model
 */
trait AuditableModel // @phpstan-ignore trait.unused
{
    public static function bootAuditableModel(): void
    {
        /** @var AuditableObserverContract $observer */
        $observer = resolve(AuditableObserverContract::class);

        static::created($observer->created(...));
        static::updated($observer->updated(...));
        static::deleted($observer->deleted(...));
    }

    public function getExcludedFromAuditing(): array
    {
        return $this->excludedFromAuditing ?? [
            'created_at',
            'updated_at',
        ];
    }
}
