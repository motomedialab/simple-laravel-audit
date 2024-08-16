<?php

namespace Motomedialab\SimpleLaravelAudit\Contracts;

interface FetchesIpAddress
{
    public function __invoke(): ?string;
}
