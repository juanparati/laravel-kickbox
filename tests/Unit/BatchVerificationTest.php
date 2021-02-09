<?php


namespace Juanparati\LaravelKickbox\Tests\Unit;


use Illuminate\Support\Str;
use Juanparati\LaravelKickbox\Kickbox;
use Juanparati\LaravelKickbox\Providers\KickboxServiceProvider;
use Juanparati\LaravelKickbox\Services\BatchVerificationService;
use Orchestra\Testbench\TestCase;


/**
 * Class BatchVerificationTest.
 *
 * @package Juanparati\LaravelKickbox\Tests\Unit
 */
class BatchVerificationTest extends TestCase
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
     * Test verification list upload.
     *
     * @throws \Throwable
     */
    public function testVerificationUpload() {
        // Bath verification only works with a live api key.
        if (Str::startsWith(config('kickbox.api_key'), 'test_'))
            $this->markTestSkipped('Requires live key');

        $emails = ['deliverable@example.com', 'rejected-email@example.com'];

        $batchService = $this->app
            ->make(Kickbox::class)
            ->service(new BatchVerificationService());

        $response = $batchService->upload($emails);

        $this->assertArrayHasKey('id', $response);

        // Get job
        $response = $batchService->verifyJob($response['id']);

        $this->assertArrayHasKey('id', $response);
    }
}