<?php

namespace Support\AutoRegisterDomains;

use Illuminate\Console\Application as ConsoleApplication;
use Illuminate\Console\Command;
use ReflectionClass;
use Support\Helper;

class RegisterDomainCommands
{
    public static function register(): void
    {
        $cacheFilePath = base_path('bootstrap/cache/domainCommandsCache.php');

        $commands = file_exists($cacheFilePath) ? include $cacheFilePath : null;

        if (is_null($commands)) {
            $commands = array_map(function ($command) {
                return Helper::filePathToNamespace($command, '/src/Domains', 'Domain');
            }, glob(base_path().'/src/Domains/*/Commands/*.php'));

            file_put_contents($cacheFilePath, '<?php '.PHP_EOL.'return '.var_export($commands, true).';');
        }

        foreach ($commands as $command) {
            if (is_subclass_of($command, Command::class) &&
                ! (new ReflectionClass($command))->isAbstract()) {
                ConsoleApplication::starting(function ($artisan) use ($command): void {
                    $artisan->resolve($command);
                });
            }
        }
    }
}
