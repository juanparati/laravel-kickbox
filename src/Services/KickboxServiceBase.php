<?php


namespace Juanparati\LaravelKickbox\Services;


use Illuminate\Http\Client\PendingRequest;
use Juanparati\LaravelKickbox\Kickbox;


/**
 * Class KickboxServiceBase.
 *
 * @package Juanparati\LaravelKickbox\Services
 */
abstract class KickboxServiceBase implements KickboxService
{

    /**
     * HTTP Client.
     *
     * @var PendingRequest
     */
    protected $httpClient = null;


    /**
     * Configuration.
     *
     * @var array
     */
    protected $config = [];


    /**
     * Kickbox instance.
     *
     * @var Kickbox|null
     */
    protected $kickboxInstance = null;


    /**
     * Inject settings and dependencies.
     *
     * @param PendingRequest $httpClient
     * @param array $config
     * @param Kickbox|null $kickbox
     * @return mixed|void
     */
    public function settings(PendingRequest $httpClient, array $config = [], Kickbox $kickbox = null) {
        $this->httpClient = $httpClient;
        $this->config = $config;
        $this->kickboxInstance = $kickbox;
    }
}