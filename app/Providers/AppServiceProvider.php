<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

// Google API
use Google\Client as Google_Client;
use Google\Service\Drive as Google_Service_Drive;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('google', function ($app, $config) {
            $client = new Google_Client();
            $client->setClientId($config['client_id']);
            $client->setClientSecret($config['client_secret']);
            $client->refreshToken($config['refresh_token']);

            $service = new Google_Service_Drive($client);
            $adapter = new GoogleDriveAdapter($service, $config['folder_id'] ?? null);
            $flysystem = new Filesystem($adapter);

            // ✅ RETURN FilesystemAdapter yang benar
            return new FilesystemAdapter($flysystem, $adapter, $config);
        });
    }
}
