<?php

use Illuminate\Database\Eloquent\Model;
use Motomedialab\SimpleLaravelAudit\Contracts\AuditorContract;

if (!function_exists('audit')) {
    function audit(string $message, array $context = []): Model
    {
        return app(AuditorContract::class)->record($message, $context);
    }
}
