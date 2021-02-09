<?php


namespace Juanparati\LaravelKickbox\Services;


use Illuminate\Http\Client\PendingRequest;
use Juanparati\LaravelKickbox\Kickbox;

/**
 * Interface KickboxService.
 *
 * @package Services
 */
interface KickboxService
{

    /**
     * Method used in order to inject the http client and cache settings.
     *
     * @param PendingRequest $httpClient
     * @param array $config
     * @param Kickbox|null $kickbox
     * @return mixed
     */
    public function settings(PendingRequest $httpClient, array $config = [], Kickbox $kickbox = null);
}