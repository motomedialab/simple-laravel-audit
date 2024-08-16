<?php

namespace Motomedialab\SimpleLaravelAudit\Contracts;

interface FetchesUserId
{
    public function __invoke(): ?int;
}
