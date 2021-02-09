<?php


namespace Juanparati\LaravelKickbox\Services;


use Illuminate\Support\Facades\Cache;
use Juanparati\LaravelKickbox\Kickbox;


/**
 * Class EmailVerificationService.
 *
 * Verify an e-mail address.
 *
 * @package Juanparati\LaravelKickbox\Services
 */
class EmailVerificationService extends KickboxServiceBase
{

    /**
     * URL segment used by the service.
     */
    const SERVICE_SEGMENT = 'verify';


    /**
     * Last headers.
     *
     * @var int
     */
    protected $lastBalance = null;


    /**
     * Verify e-mail address.
     *
     * @param string $email
     * @param bool $useCache
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function verify(string $email, $useCache = true) {

        $cacheTime = $useCache ? ($this->config['cache']['time'] ?? null) : null;

        if ($cacheTime) {
            if ($response = Cache::store($this->config['cache']['store'] ?? 'default')->get($this->cacheKey($email)))
                return $response;
        }

        $httpClient = clone $this->httpClient;

        $response = $httpClient->get(
            $this->config['base_url'] . static::SERVICE_SEGMENT,
            [
                'email'   => $email,
                'apikey'  => $this->config['api_key'],
                'timeout' => $this->config['request']['timeout'] ?? 6000
            ]
        );

        $response->throw();

        if ($this->kickboxInstance instanceof Kickbox)
            $this->kickboxInstance->_countVerification();

        $this->kickboxInstance->_setLastBalance($response->header('X-Kickbox-Balance'));

        $response = $response->json();

        if ($cacheTime) {
            $response['from_cache'] = true;

            Cache::store(
                $this->config['cache']['store'])->put(
                    $this->cacheKey($email),
                    $response,
                    $cacheTime
            );
        }

        $response['from_cache'] = false;

        return $response;
    }



    /**
     * Get the cache key.
     *
     * @param string $email
     * @return string
     */
    protected function cacheKey(string $email) : string {
        return ($this->config['cache']['prefix'] ?? 'Kickbox::') . 'verify:' . md5($email);
    }

}