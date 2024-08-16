<?php

namespace Motomedialab\SimpleLaravelAudit\Auditors;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Motomedialab\SimpleLaravelAudit\Contracts\AuditorContract;
use Motomedialab\SimpleLaravelAudit\Contracts\FetchesIpAddress;
use Motomedialab\SimpleLaravelAudit\Contracts\FetchesUserId;

class SimpleAuditor implements AuditorContract
{
    public function record(string $message, array $context = []): Model
    {
        $ipAddress = App::call(FetchesIpAddress::class);
        $userId = App::call(FetchesUserId::class);

        return config('simple-auditor.model')::create([
            'message' => $message,
            'context' => $context,
            'ip_address' => $ipAddress,
            'user_id' => $userId,
        ]);
    }

    public function audit(string $message, array $context = []): Model
    {
        return $this->record($message, $context);
    }
}
