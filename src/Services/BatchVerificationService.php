<?php


namespace Juanparati\LaravelKickbox\Services;



use Juanparati\LaravelKickbox\Kickbox;

/**
 * Class BatchVerificationService.
 *
 * Verify an e-mail address.
 *
 * @package Juanparati\LaravelKickbox\Services
 */
class BatchVerificationService extends KickboxServiceBase
{

    /**
     * URL segment used by the service.
     */
    const SERVICE_SEGMENT = 'verify-batch';


    /**
     * Upload a list
     * @param array $list
     * @param string|null $callbackUrl
     * @param string|null $jobName
     * @return array
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function upload(array $list, string $callbackUrl = null, string $jobName = null) : array {

        $url = $this->config['base_url'] . static::SERVICE_SEGMENT . '?apikey=' . $this->config['api_key'];

        $headers = [
            'X-Kickbox-Filename' => $jobName ?: 'API_' . date('m-d-Y-H-i-s')
        ];

        if ($callbackUrl)
            $headers['X-Kickbox-Callback'] = $callbackUrl;

        $httpClient = clone $this->httpClient;

        $response = $httpClient
            ->timeout($this->config['request']['timeout'] * 8)  // Bath upload can take much more time
            ->withHeaders($headers)
            ->withBody(implode(PHP_EOL, $list), 'text/csv')
            ->put($url);

        if ($this->kickboxInstance instanceof Kickbox)
            $this->kickboxInstance->_countVerification(count($list));

        $response->throw();

        return $response->json();
    }


    /**
     * Verify job status.
     *
     * @param int $job
     * @return array
     * @throws \Illuminate\Http\Client\RequestException
     */
    public function verifyJob(int $job) : array {

        $httpClient = clone $this->httpClient;

        $response = $httpClient->get($this->config['base_url'] . static::SERVICE_SEGMENT . "/$job/" , [
            'apikey' => $this->config['api_key']
        ]);

        $response->throw();

        return $response->json();
    }

}