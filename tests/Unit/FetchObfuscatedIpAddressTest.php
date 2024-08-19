<?php

use Illuminate\Http\Request;
use Motomedialab\SimpleLaravelAudit\Actions\FetchObfuscatedIpAddress;
use Motomedialab\SimpleLaravelAudit\Tests\TestCase;

uses(TestCase::class);

it('can obfuscate an ipv4 address', function () {

    // spoof an IPv4 address

    app()->bind('request', fn () => new Request(server: ['REMOTE_ADDR' => '81.146.11.42']));

    $method = new FetchObfuscatedIpAddress();

    expect($method())->toBe('xx.xxx.11.42');
});

it('can obfuscate an ipv6 address', function () {

    // spoof an IPv6 address
    app()->bind('request', fn () => new Request(server: ['REMOTE_ADDR' => '2001:db8:85a3::8a2e:370:7334']));

    $method = new FetchObfuscatedIpAddress();

    expect($method())->toEqual('xxxx:xxx:85a3::8a2e:370:7334');
});

it('can handle a null value as a possible response', function () {
    app()->bind('request', fn () => new Request(server: ['REMOTE_ADDR' => null]));

    $method = new FetchObfuscatedIpAddress();

    expect($method())->toBeNull();
});
