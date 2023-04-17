<?php

namespace Support\AutoRegisterDomains;

use Support\Helper;

class RegisterDomainServiceProviders
{
    public static function register(): void
    {
        $app = app();
        $cacheFilePath = base_path('bootstrap/cache/domainServiceProviderCache.php');
        $serviceProviders = file_exists($cacheFilePath) ? include $cacheFilePath : null;

        if (is_null($serviceProviders)) {
            $serviceProviders = array_map(function ($serviceProvider) {
                return Helper::filePathToNamespace($serviceProvider, '/src/Domains', 'Domain');
            }, glob(base_path().'/src/Domains/*/*ServiceProvider.php'));

            file_put_contents(
                $cacheFilePath,
                '<?php '.PHP_EOL.'return '.var_export($serviceProviders, true).';'
            );
        }

        foreach ($serviceProviders as $serviceProvider) {
            $app->register($serviceProvider);
        }
    }
}
