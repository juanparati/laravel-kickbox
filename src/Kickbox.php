<?php

namespace Juanparati\LaravelKickbox;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Juanparati\LaravelKickbox\Exceptions\UnknownServiceException;
use Juanparati\LaravelKickbox\Services\BatchVerificationService;
use Juanparati\LaravelKickbox\Services\EmailVerificationService;
use Juanparati\LaravelKickbox\Services\KickboxService;


/**
 * Class Kickbox.
 *
 * @package Juanparati\LaravelKickbox
 */
class Kickbox
{

    const REQUESTS_COUNT_CACHE_KEY = 'KickboxCountRequests';
    const BALANCE_CACHE_KEY        = 'KickboxLastBalance';


    /**
     * Default configuration.
     *
     * @var array
     */
    protected $config;


    /**
     * Http client.
     *
     * @var \Illuminate\Http\Client\PendingRequest
     */
    protected $httpClient;


    /**
     * Available services.
     *
     * @var string[]
     */
    protected $services = [
        'email' => EmailVerificationService::class,
        'batch' => BatchVerificationService::class,
    ];


    /**
     * Conversion constructor.
     *
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {
        $this->config = $config;

        $timeout = ($this->config['request']['timeout'] ?? 30000) / 1000;

        $this->httpClient = Http::timeout($timeout);

        if (!empty($this->config['request']['retry']))
            $this->httpClient->retry($this->config['request']['retry'], $this->config['request']['retry_wait'] ?? 100);
    }


    /**
     * Service call.
     *
     * @param $service
     * @return KickboxService
     * @throws \Throwable
     */
    public function service($service) : KickboxService
    {

        if (is_string($service)) {
            $service = $this->services[$service] ?? null;

            throw_if(!$service, new UnknownServiceException('Unknown service'));
        }

        throw_if(!($service instanceof KickboxService), new UnknownServiceException('Service doesn\'t implement KickboxService'));

        /**
         * @var $service KickboxService
         */
        $service->settings($this->httpClient, $this->config, $this);

        return $service;
    }


    /**
     * Obtain the number of verifications in the last minute.
     *
     * @return int
     */
    public function getVerificationsInLastMinute() : int {
        return Cache::get(static::REQUESTS_COUNT_CACHE_KEY, 0);
    }


    /**
     * Get the last balance.
     *
     * @return int
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     */
    public function getLastBalance() : int {
        $balance = Cache::get(static::BALANCE_CACHE_KEY);

        if ($balance !== null)
            return $balance;

        $this->service(new EmailVerificationService())->verify('example@example.org', false);

        return $this->getLastBalance();
    }


    /**
     * Count a new verification.
     *
     * @param int $verifications
     */
    public function _countVerification($verifications = 1) : void {
        Cache::put(
            static::REQUESTS_COUNT_CACHE_KEY,
            Cache::increment(static::REQUESTS_COUNT_CACHE_KEY, $verifications),
            60
        );
    }


    /**
     * Set the last retrieved balance.
     *
     * @param int $balance
     */
    public function _setLastBalance(int $balance) : void {
        Cache::put(static::BALANCE_CACHE_KEY, $balance);
    }
}