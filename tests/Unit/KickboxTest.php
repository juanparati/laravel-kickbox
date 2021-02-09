<?php


namespace Juanparati\LaravelKickbox\Tests\Unit;


use Juanparati\LaravelKickbox\Kickbox;
use Juanparati\LaravelKickbox\Providers\KickboxServiceProvider;
use Juanparati\LaravelKickbox\Services\EmailVerificationService;
use Orchestra\Testbench\TestCase;


/**
 * Class KickboxTest.
 *
 * @package Juanparati\LaravelKickbox\Tests\Unit
 */
class KickboxTest extends TestCase
{
    /**
     * Load service providers.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return string[]
     */
    protected function getPackageProviders($app)
    {
        return [KickboxServiceProvider::class];
    }


    /**
     * Test last balance.
     *
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     */
    public function testLastBalance() {
        $kickbox = $this->app->make(Kickbox::class);
        $this->assertGreaterThanOrEqual(0, $kickbox->getLastBalance());
    }
}