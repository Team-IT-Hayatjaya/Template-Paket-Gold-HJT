<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Active Client Package
    |--------------------------------------------------------------------------
    |
    | Read from SITE_PACKAGE environment variable (silver, gold, platinum).
    |
    */
    'default' => env('SITE_PACKAGE', 'gold'),

    /*
    |--------------------------------------------------------------------------
    | Tier Definitions
    |--------------------------------------------------------------------------
    |
    | Features and Quotas per package tier.
    |
    */
    'packages' => [
        'silver' => [
            'name' => 'Silver',
            'features' => [
                'content_cms',
                'ppid_form',
            ],
            'quotas' => [
                'content_updates_per_month' => 5,
                'backup_frequency' => 'monthly',
            ],
        ],

        'gold' => [
            'name' => 'Gold',
            'features' => [
                'content_cms',
                'ppid_form',
                'ppid_tracking',
                'accessibility',
                'security_hardening',
                'custom_branding',
                'content_quota_monitor',
            ],
            'quotas' => [
                'content_updates_per_month' => 20,
                'backup_frequency' => 'weekly',
            ],
        ],

        'platinum' => [
            'name' => 'Platinum',
            'features' => [
                'content_cms',
                'ppid_form',
                'ppid_tracking',
                'accessibility',
                'security_hardening',
                'custom_branding',
                'content_quota_monitor',
                'subdomains',
                'open_data',
                'whatsapp_bot',
            ],
            'quotas' => [
                'content_updates_per_month' => null,
                'backup_frequency' => 'daily',
            ],
        ],
    ],
];
