<?php

namespace Motomedialab\SimpleLaravelAudit\Contracts;

use Motomedialab\SimpleLaravelAudit\Data\GuardData;

interface FetchesUserId
{
    public function __invoke(): ?GuardData;
}
