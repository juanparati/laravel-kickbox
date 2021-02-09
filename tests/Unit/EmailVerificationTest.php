<?php


namespace Juanparati\LaravelKickbox\Tests\Unit;


use Juanparati\LaravelKickbox\Kickbox;
use Juanparati\LaravelKickbox\Providers\KickboxServiceProvider;
use Juanparati\LaravelKickbox\Services\EmailVerificationService;
use Orchestra\Testbench\TestCase;


/**
 * Class EmailVerificationTest.
 *
 * @package Juanparati\LaravelKickbox\Tests\Unit
 */
class EmailVerificationTest extends TestCase
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
     * Test request for deliverable e-mail.
     *
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     */
    public function testDeliverableVerification() {
        $email = 'deliverable@example.com';

        $response = $this->app
            ->make(Kickbox::class)
            ->service('email')
            ->verify($email);

        $this->assertEquals('deliverable', $response['result']);
        $this->assertEquals('accepted_email', $response['reason']);
        $this->assertEquals(false, $response['from_cache']);
        $this->assertEquals($email, $response['email']);
    }


    /**
     * Test request for non-deliverable e-mail.
     *
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Throwable
     */
    public function testUndeliverableVerification() {
        $email = 'rejected-email@example.com';

        $response = $this->app
            ->make(Kickbox::class)
            ->service(new EmailVerificationService())
            ->verify($email);

        $this->assertEquals('undeliverable', $response['result']);
        $this->assertEquals('rejected_email', $response['reason']);
        $this->assertEquals(false, $response['from_cache']);
        $this->assertEquals($email, $response['email']);
    }

}