# Laravel Kickbox

A Laravel interface for the [Kickbox](https://kickbox.com) service.


## Installation

    composer require juanparati/laravel-kickbox

Facade registration (optional):

    'aliases' => [
        ...
        'Kickbox' => \Juanparati\LaravelKickbox\Facades\KickboxFacade::class,
        ...
    ]


## Configuration

Publish configuration file:

    artisan vendor:publish --provider="Juanparati\LaravelKickbox\Providers\KickboxServiceProvider"

Add the Kickbox api key into your configuration file.


## Usage

Verify an e-mail:

    $result = Kickbox::service('email')->verify('example@example.org');


Get last balance:

    $result = Kickbox::getLastBalance();


Count the number of verifications in the last minute:

    Kickbox::getVerificationsInLastMinute();


Verify a list of e-mails using the batch verification:

    $list = ['example@example.org', 'example@example.com'];
    $job = Kickbox::service('batch')->upload($list, 'https://mycallbackurl.com/foo/bar');


Verify job status

    $status = Kickbox::service('batch')->verifyJob($job['id']);


## Cache usage

It's possible to define a default cache for the email verification service. This will avoid to perform duplicate verifications of the same e-mail.

See the configuration file for more details.