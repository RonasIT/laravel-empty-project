<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @codeCoverageIgnore
 */
class Init extends Command
{
    protected $signature = 'init {application-name : The application name }';

    protected $description = 'Initialize required project parameters to run DEV environment';

    public function handle(): void
    {
        $appName = $this->argument('application-name');
        $kebabName = Str::kebab($appName);

        $this->updateConfigFile('.env.testing', '=', [
            'DATA_COLLECTOR_KEY' => "{$kebabName}-local"
        ]);

        $this->updateConfigFile('.env', '=', [
            'APP_NAME' => $appName,
            'SWAGGER_REMOTE_DRIVER_KEY' => "{$kebabName}-local"
        ]);

        $this->updateConfigFile('.env.development', '=', [
            'APP_NAME' => $appName,
            'DATA_COLLECTOR_KEY' => "{$kebabName}"
        ]);

        $this->updateConfigFile('.env.ci-testing', '=', [
            'DATA_COLLECTOR_KEY' => "{$kebabName}"
        ]);

        $this->info('Project initialized successfully');

        if ($this->confirm('Do you want generate admin user?', true)) {
            $this->createAdminUser($kebabName);
        }
    }

    protected function createAdminUser($kebabName): void
    {
        $defaultPassword = substr(md5(uniqid()), 0, 8);

        $this->publishMigration([
            'name' => $this->ask('Please enter admin name', 'Admin'),
            'email' => $this->ask('Please enter admin email', "admin@{$kebabName}.com"),
            'password' => $this->ask('Please enter admin password', $defaultPassword),
            'role_id' => Role::ADMIN
        ]);
    }

    protected function addQuotes($string): string
    {
        return (Str::contains($string, ' ')) ? "\"{$string}\"" : $string;
    }

    protected function publishMigration($admin): void
    {
        $data = view('add_default_user')->with($admin)->render();
        $fileName = Carbon::now()->format('Y_m_d_His') . '_add_default_user.php';

        file_put_contents("database/migrations/{$fileName}", "<?php\n\n{$data}");
    }

    protected function updateConfigFile($fileName, $separator, $data): void
    {
        $parsed = file_get_contents($fileName);

        $lines = explode("\n", $parsed);

        foreach ($lines as &$line) {
            foreach ($data as $key => $value) {
                if (Str::contains($line, "{$key}{$separator}")) {
                    $exploded = explode($separator, $line);
                    $key = array_shift($exploded);
                    $value = $this->addQuotes($value);
                    $line = "{$key}{$separator}{$value}";
                }
            }
        }

        $ymlSettings = implode("\n", $lines);

        file_put_contents($fileName, $ymlSettings);
    }
}
