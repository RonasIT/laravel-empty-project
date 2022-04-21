<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class Init extends Command
{
    protected $signature = 'init {application-name : The application name }';

    protected $description = 'Initialize required project parameters to run DEV environment';

    public function handle()
    {
        $appName = $this->argument('application-name');
        $kebabName = Str::kebab($appName);

        $this->updateConfigFile('.env.testing', '=', [
            'APP_NAME' => $appName,
            'DATA_COLLECTOR_KEY' => "{$kebabName}-local"
        ]);

        $this->updateConfigFile('.env', '=', [
            'APP_NAME' => $appName,
            'DATA_COLLECTOR_KEY' => "{$kebabName}-local"
        ]);

        $this->updateConfigFile('.gitlab-ci.yml', ': ', [
            'CI_PROJECT_NAME' => $kebabName,
            'DOMAIN' => "api.{$kebabName}.ronasit.com",
            'APP_NAME' => $appName,
            'DATA_COLLECTOR_KEY' => $kebabName
        ]);

        if ($this->confirm('Do you want generate admin user?', true)) {
            $this->createAdminUser($kebabName);
        }
    }

    protected function createAdminUser($kebabName)
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

    protected function publishMigration($admin)
    {
        $data = view('add_default_user')->with($admin)->render();
        $fileName = Carbon::now()->format('Y_m_d_His') . '_add_default_user.php';

        return file_put_contents("database/migrations/{$fileName}", "<?php\n\n{$data}");
    }

    protected function updateConfigFile($fileName, $separator, $data)
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
