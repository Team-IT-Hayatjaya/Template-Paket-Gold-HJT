<?php

use App\Support\Package;

test('default package reads silver from environment or config', function () {
    expect(Package::current())->toBe('silver');
    expect(Package::is('silver'))->toBeTrue();
});

test('silver tier features and quotas are correctly evaluated', function () {
    config(['packages.default' => 'silver']);

    expect(Package::has('content_cms'))->toBeTrue()
        ->and(Package::has('ppid_form'))->toBeTrue()
        ->and(Package::has('ppid_tracking'))->toBeFalse()
        ->and(Package::has('accessibility'))->toBeFalse()
        ->and(Package::has('subdomains'))->toBeFalse()
        ->and(Package::has('open_data'))->toBeFalse()
        ->and(Package::has('whatsapp_bot'))->toBeFalse()
        ->and(Package::limit('content_updates_per_month'))->toBe(5)
        ->and(Package::limit('backup_frequency'))->toBe('monthly');
});

test('gold tier features and quotas are correctly evaluated', function () {
    config(['packages.default' => 'gold']);

    expect(Package::has('content_cms'))->toBeTrue()
        ->and(Package::has('ppid_form'))->toBeTrue()
        ->and(Package::has('ppid_tracking'))->toBeTrue()
        ->and(Package::has('accessibility'))->toBeTrue()
        ->and(Package::has('subdomains'))->toBeFalse()
        ->and(Package::has('open_data'))->toBeFalse()
        ->and(Package::has('whatsapp_bot'))->toBeFalse()
        ->and(Package::limit('content_updates_per_month'))->toBe(20)
        ->and(Package::limit('backup_frequency'))->toBe('weekly');
});

test('platinum tier features and quotas are correctly evaluated', function () {
    config(['packages.default' => 'platinum']);

    expect(Package::has('content_cms'))->toBeTrue()
        ->and(Package::has('ppid_form'))->toBeTrue()
        ->and(Package::has('ppid_tracking'))->toBeTrue()
        ->and(Package::has('accessibility'))->toBeTrue()
        ->and(Package::has('subdomains'))->toBeTrue()
        ->and(Package::has('open_data'))->toBeTrue()
        ->and(Package::has('whatsapp_bot'))->toBeTrue()
        ->and(Package::limit('content_updates_per_month'))->toBeNull()
        ->and(Package::limit('backup_frequency'))->toBe('daily');
});

test('package alias is registered globally without namespace import', function () {
    expect(\Package::has('content_cms'))->toBeTrue();
    expect(\Package::limit('backup_frequency'))->not()->toBeNull();
});
