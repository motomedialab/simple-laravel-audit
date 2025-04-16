<?php

namespace Motomedialab\SimpleLaravelAudit\Actions;

use Illuminate\Support\Facades\Auth;
use Motomedialab\SimpleLaravelAudit\Contracts\FetchesUserId;
use Motomedialab\SimpleLaravelAudit\Data\GuardData;

class FetchUserId implements FetchesUserId
{
    public function __invoke(): ?GuardData
    {
        $guard = Auth::getDefaultDriver();
        $driver = auth($guard);

        if (!$driver->check()) {
            return null;
        }

        return new GuardData($guard, $driver->id());
    }
}
