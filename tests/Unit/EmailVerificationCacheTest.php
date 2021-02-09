<?php


namespace Juanparati\LaravelKickbox\Tests\Unit;


use Illuminate\Support\Facades\Cache;
use Juanparati\LaravelKickbox\Kickbox;
use Juanparati\LaravelKickbox\Providers\KickboxServiceProvider;
use Juanparati\LaravelKickbox\Services\EmailVerificationService;
use Orchestra\Testbench\TestCase;


class EmailVerificationCacheTest extends TestCase
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
     * Prepare the environment and configuration.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app) {
        $app['config']->set('kickbox.cache', [
            'time'   => 3,
            'store'  => 'array',
            'prefix' => 'KickboxTest:'
        ]);
    }


    /**
     * Test request for deliverable e-mail.
     *
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     */
    public function testDeliverableVerification() {
        $email = 'deliverable@example.com';

        $kickbox =  $this->app->make(Kickbox::class);
        $kickboxService = $kickbox->service(new EmailVerificationService());

        $response = $kickboxService->verify($email);
        $this->assertEquals(false, $response['from_cache']);

        $this->assertEquals(1, $kickbox->getVerificationsInLastMinute());

        sleep(1);

        $response = $kickboxService->verify($email);

        $this->assertEquals('deliverable', $response['result']);
        $this->assertEquals('accepted_email', $response['reason']);
        $this->assertEquals(true, $response['from_cache']);
        $this->assertEquals($email, $response['email']);

        $this->assertEquals(1, $kickbox->getVerificationsInLastMinute());

        sleep(6);

        $response = $kickboxService->verify($email);

        $this->assertEquals(false, $response['from_cache']);
        $this->assertEquals($email, $response['email']);

        $this->assertEquals(2, $kickbox->getVerificationsInLastMinute());
    }


    /**
     * Test request when cache is invalidated.
     *
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     */
    public function testInvalidateCache() {

        $email = 'invalid-smtp@example.com';

        $kickboxService = $this->app
            ->make(Kickbox::class)
            ->service(new EmailVerificationService());

        $response = $kickboxService->verify($email);
        $this->assertEquals(false, $response['from_cache']);

        sleep(1);

        $response = $kickboxService->verify($email, false);

        $this->assertEquals('undeliverable', $response['result']);
        $this->assertEquals('invalid_smtp', $response['reason']);
        $this->assertEquals(false, $response['from_cache']);
        $this->assertEquals($email, $response['email']);
    }
}