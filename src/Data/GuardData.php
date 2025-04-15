<?php

namespace Motomedialab\SimpleLaravelAudit\Data;

readonly class GuardData
{
    public function __construct(private string $guard, private int $id)
    {
        //
    }

    public function guard(): string
    {
        return $this->guard;
    }

    public function authId(): int
    {
        return $this->id;
    }
}