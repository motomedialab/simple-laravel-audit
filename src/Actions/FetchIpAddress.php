<?php

namespace Motomedialab\SimpleLaravelAudit\Actions;

use Motomedialab\SimpleLaravelAudit\Contracts\FetchesIpAddress;

class FetchIpAddress implements FetchesIpAddress
{
    public function __invoke(): ?string
    {
        return request()->ip();
    }
}
