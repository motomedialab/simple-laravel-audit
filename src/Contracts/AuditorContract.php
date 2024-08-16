<?php

namespace Motomedialab\SimpleLaravelAudit\Contracts;

use Illuminate\Database\Eloquent\Model;

interface AuditorContract
{
    public function record(string $message, array $context = []): Model;
}
