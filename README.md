## Installation

```
composer require salamwaddah/laravel-smsglobal-notifications-channel
```

## Configure

In your `services.php` config file add the following configs.

```
    // ... 
    
    'sms_global' => [
        'debug' => env('SMS_GLOBAL_DEBUG', true),
        'api_key' => env('SMS_GLOBAL_API_KEY'),
        'api_secret' => env('SMS_GLOBAL_API_SECRET'),
        'origin' => 'YourCompanyName',
    ],
```

## Debug Mode

Debug mode is turn on by default, which means SMS will not be actually sent, instead only a log record will be added to `/storage/logs/laravel.log`

In your `services.php` change the value of `sms_global.debug` to `false`
