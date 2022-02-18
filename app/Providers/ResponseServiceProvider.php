<?php

namespace App\Providers;

use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @param ResponseFactory $responseFactory
     * @return void
     */
    public function boot(ResponseFactory $responseFactory)
    {
        // Success api responses
        $responseFactory->macro(
            'success',
            function ($data, int $status, string $message = '', array $headers = []) use ($responseFactory) {
                $responseBody = [
                    'success' => true,
                    'message' => $message,
                    'data' => $data
                ];

                return $responseFactory->make($responseBody, $status, $headers);
            }
        );

        $responseFactory->macro(
            'error',
            function ($errors, int $status, string $message = '', array $headers = []) use ($responseFactory) {
                $responseBody = [
                    'success' => false,
                    'message' => $message,
                    'errors' => $errors
                ];

                return $responseFactory->make($responseBody, $status, $headers);
            }
        );
    }
}
