# Simple Laravel Auditing

![GitHub Actions](https://github.com/motomedialab/simple-laravel-audit/actions/workflows/main.yml/badge.svg)

A lightweight package to provide the ability and flexibility to quickly and easily audit events and actions that happen within
your Laravel application.

## Installation

You can install the package via composer:

```bash
composer require motomedialab/simple-laravel-audit
```

Once you've done this, run your migrations. This will create a table called `audit_logs`.

```
php artisan migrate
```

## Configuration

Out of the box there isn't any requirement to configure the package. It will work with the default settings.

However, if you'd like to customise any options, such as the table name or classes that are utilised, you can publish the config file
and change any of the options. It's designed to be flexible allowing you to change IP address resolution, user ID resolution,
table name and more.

```
php artisan vendor:publish --tag=simple-auditor
```

### Obfuscating the IP address for compliance

You can easily obfuscate IP addresses that are submitted to the database by setting the `SIMPLE_AUDITOR_OBFUSCATE_IP`
variable in your `.env` file to true. This will strip the first two octets of an IP address, ensuring it meets various
compliance laws, such as GDPR. Behind the scenes this switches the default IP address fetcher with an Obfuscated IP fetcher.

### Setting the retention duration

You can define how many days your logs should be kept for by setting the `SIMPLE_AUDITOR_RETENTION` in your `.env` file.
If you want to keep all logs indefinitely, set this to `0`.

```dotenv
SIMPLE_AUDITOR_RETENTION=30 # retain for 30 days
SIMPLE_AUDITOR_RETENTION=0 # retain indefinitely
```

Every time the audit logs are pruned, this will be recorded as an audit log itself.

## Usage

There are multiple ways you can use this package. The most common way is to use the `audit` helper function.

### Using the global audit helper

The `audit` helper function is an easy way to quickly log to the audits table. This function takes a string
as the first argument, and an optional array (context) as the second argument. This will only work if you
don't already have a global function called `audit`.

```php
audit('Action performed', ['more_data' => 'Goes here']);
```

### Using the facade

Some people love using Laravel's Facades due to their ease of use and static nature.

```php
// import the facade
use Motomedialab\SimpleLaravelAudit\Facades\AuditFacade;

// create our audit log
AuditFacade::audit('Action performed', ['more_data' => 'Goes here']);
```

### Binding to events

If you want to audit an event that happens within your application, you can do so by using the `IsAuditableEvent`
interface. Coupled with `AuditableEvent`, this will automatically log the event to the audit log.

Here's an example of an event that utilises the `IsAuditableEvent` interface:

```php
// import our contract & trait
use Motomedialab\SimpleLaravelAudit\Contracts\IsAuditableEvent;
use Motomedialab\SimpleLaravelAudit\Traits\AuditableEvent;

class MyCustomEvent implements IsAuditableEvent
{
    use AuditableEvent;

    public function handle()
    {
        // ToDo: your event logic.
    }
    
    // optional - by default will be handled by the AuditableEvent trait
    public function getAuditMessage(): string
    {
        return 'Action performed';
    }
    
    // optional - by default will be handled by the AuditableEvent trait
    public function getAuditContext(): array
    {
        return ['more_data' => 'Goes here'];
    }
}
```

### Using the `AuditableModel` trait on Models

If you have a model that you'd like to be audited on change, you can use the `AuditableModel` trait.
By default, this will record all creations, updates and deletions for this model to the audit log.
This uses Laravel model observers to listen for changes. By default, the `created_at` and `updated_at` columns
are excluded from auditing.

```php
use Illuminate\Database\Eloquent\Model;
use Motomedialab\SimpleLaravelAudit\Traits\AuditableModel;

class YourModel extends Model
{
    use AuditableModel;
    
    /**
    * An array of columns that shouldn't be audited.
    * @var array|string[] 
    */
    protected array $excludedFromAuditing = [
        'created_at',
        'updated_at',
    ];
}
```

#### Customising the `AuditableModel` functionality

If you'd like to expand the functionality of the `AuditableModel` trait, you can override its observer
by configuring the `observer` key in the config file. This will allow you to create your own model observer.

```php
use Motomedialab\SimpleLaravelAudit\Observers\AuditableModelObserver as BaseObserver;

class AuditableObserver extends BaseObserver
{
    // your custom classes here
    // see https://laravel.com/docs/11.x/eloquent#observers for more information
}
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security-related issues, please email chris@motocom.co.uk instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
