<?php

namespace Motomedialab\SimpleLaravelAudit\Actions;

use Motomedialab\SimpleLaravelAudit\Contracts\FetchesUserId;

class FetchUserId implements FetchesUserId
{
    public function __invoke(): ?int
    {
        return auth()->id();
    }
}
