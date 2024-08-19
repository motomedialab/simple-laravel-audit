<?php

namespace Motomedialab\SimpleLaravelAudit\Actions;

use Motomedialab\SimpleLaravelAudit\Contracts\FetchesIpAddress;

class FetchObfuscatedIpAddress implements FetchesIpAddress
{
    public function __invoke(): ?string
    {
        $ip = request()->ip();
        $dotCount = 0;

        for ($i = 0; $i < strlen($ip); $i++) {
            if ($ip[$i] === '.' || $ip[$i] === ':') {
                $dotCount++;
                continue;
            }

            if ($dotCount === 2) {
                break;
            }

            $ip[$i] = 'x';
        }

        return $ip;
    }
}
