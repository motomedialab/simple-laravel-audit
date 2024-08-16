<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Audit logs table name
    |--------------------------------------------------------------------------
    |
    | This is the name of the table that will be created by the migration
    | to store the audit logs. You may change this value if you prefer.
    |
    */
    'table_name' => env('SIMPLE_AUDITOR_TABLE_NAME', 'audit_logs'),

    /*
    |--------------------------------------------------------------------------
    | The model to use for the audit logs
    |--------------------------------------------------------------------------
    |
    | This is the model that will be used to store the audit logs. You may
    | change this value if you prefer to use a custom model to provide additional
    | functionality.
    |
    */
    'model' => Motomedialab\SimpleLaravelAudit\Models\AuditLog::class,

    /*
    |--------------------------------------------------------------------------
    | Observer used by the AuditableModel trait
    |--------------------------------------------------------------------------
    |
    | You can override this to watch for additional events on the model.
    |
    */
    'observer' => Motomedialab\SimpleLaravelAudit\Observers\AuditorModelObserver::class,

    /*
    |--------------------------------------------------------------------------
    | The prune job that runs daily
    |--------------------------------------------------------------------------
    |
    | This is the job that is run on a daily basis to prune old audit logs.
    | You can change this value to extend the job and provide additional functionality.
    |
    */
    'prune_job' => Motomedialab\SimpleLaravelAudit\Jobs\PruneOldAuditLogs::class,

    /*
    |--------------------------------------------------------------------------
    | The default retention period for audit logs
    |--------------------------------------------------------------------------
    |
    | This is the default number of days that audit logs will be retained for
    | before being deleted by the prune job. You can change this value to
    | retain logs for a different period.
    |
    */
    'retain_logs_for_days' => env('SIMPLE_AUDITOR_RETENTION', 90),

    /*
    |--------------------------------------------------------------------------
    | IP Address Fetcher
    |--------------------------------------------------------------------------
    |
    | The IP address fetcher determines the IP address to record to the database.
    | You can change this value to use a custom IP address fetcher. It must implement
    | the Motomedialab\SimpleLaravelAudit\Contracts\FetchesIpAddress interface.
    |
    */
    'ip_address_fetcher' => Motomedialab\SimpleLaravelAudit\Actions\FetchIpAddress::class,

    /*
    |--------------------------------------------------------------------------
    | User ID fetcher
    |--------------------------------------------------------------------------
    |
    | The user ID fetcher resolves the current User logged in to the application.
    | You can change this value to use a custom user ID fetcher. It must implement
    | the Motomedialab\SimpleLaravelAudit\Contracts\FetchesUserId interface.
    |
    */
    'user_id_fetcher' => Motomedialab\SimpleLaravelAudit\Actions\FetchUserId::class,
];
