<?php

namespace Motomedialab\SimpleLaravelAudit\Actions;

use Motomedialab\SimpleLaravelAudit\Contracts\FetchesIpAddress;

class FetchIpAddress implements FetchesIpAddress
{
    public function __invoke(): ?string
    {
        if (app()->runningInConsole()) {
            return 'console';
        }

        return request()->ip();
    }
}
