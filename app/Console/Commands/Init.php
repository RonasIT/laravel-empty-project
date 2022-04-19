<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class Init extends Command
{
    protected $signature = 'init';

    protected $description = 'Initialize required project parameters to run DEV environment';

    public function handle()
    {
        $appName = $this->ask('Please set application name');
        $kebabName = Str::kebab($appName);

        $this->updateDotEnv('.env.testing', [
            'APP_NAME' => $appName,
            'DATA_COLLECTOR_KEY' => $kebabName
        ]);

        $this->updateDotEnv('.env', [
            'APP_NAME' => $appName,
            'DATA_COLLECTOR_KEY' => $kebabName
        ]);

        $this->updateGitLabCiYaml($appName, $kebabName);

        if ($this->confirm('Do you want generate admin user?', true)) {
            $this->createAdminUser("admin@{$kebabName}.com");
        }
    }

    protected function updateDotEnv(string $fileName, array $data)
    {
        $dotEnv = $this->parseDotEnv($fileName);

        foreach ($dotEnv as &$block) {
            $keysToUpdate = array_intersect_key($data, $block);

            $block = array_merge($block, $keysToUpdate);
        }

        $this->dumpDotEnv($dotEnv, $fileName);
    }

    protected function updateGitLabCiYaml($appName, $kebabName)
    {
        $ymlSettings = file_get_contents('.gitlab-ci.yml');

        $ymlSettings = Str::replaceFirst('CI_PROJECT_NAME: laravel', "CI_PROJECT_NAME: {$kebabName}", $ymlSettings);
        $ymlSettings = Str::replaceFirst('DOMAIN: laravel.ronasit.com', "DOMAIN: {$kebabName}.ronasit.com", $ymlSettings);
        $ymlSettings = Str::replaceFirst('APP_NAME: Laravel', "APP_NAME: \"{$appName}\"", $ymlSettings);
        $ymlSettings = Str::replaceFirst('DATA_COLLECTOR_KEY: laravel', "DATA_COLLECTOR_KEY: {$kebabName}", $ymlSettings);

        file_put_contents('.gitlab-ci.yml', $ymlSettings);
    }

    protected function createAdminUser($defaultEmail)
    {
        $defaultPassword = substr(md5(uniqid()), 0, 8);

        $this->publishMigration([
            'name' => $this->ask('Please enter admin name', 'Admin'),
            'email' => $this->ask('Please enter admin email', $defaultEmail),
            'password' => $this->ask('Please enter admin password', $defaultPassword),
            'role_id' => Role::ADMIN
        ]);
    }

    protected function parseDotEnv($fileName): array
    {
        $dotEnvContent = file_get_contents($fileName);

        $lines = explode("\n", $dotEnvContent);

        $result = [[]];

        $lastLineIndex = array_key_last($lines);

        foreach ($lines as $index => $line) {
            if (!empty($line)) {
                $exploded = explode('=', $line);

                $block = array_pop($result);

                $block[array_shift($exploded)] = implode('=', $exploded);

                $result[] = $block;
            } elseif ($index !== $lastLineIndex) {
                $result[] = [];
            }
        }

        return $result;
    }

    protected function dumpDotEnv(array $parsed, $fileName)
    {
        $result = '';

        $lastBlockIndex = array_key_last($parsed);

        foreach ($parsed as $index => $block) {
            foreach ($block as $key => $value) {
                if (Str::contains($value, ' ')) {
                    $value = "\"{$value}\"";
                }

                $result .= "{$key}={$value}\n";
            }

            if ($index !== $lastBlockIndex) {
                $result .= "\n";
            }
        }

        return file_put_contents($fileName, $result);
    }

    public function publishMigration($admin)
    {
        $data = view('add_default_user')->with($admin)->render();
        $fileName = Carbon::now()->format('Y_m_d_His') . '_add_default_user.php';

        return file_put_contents("database/migrations/{$fileName}", "<?php\n\n{$data}");
    }
}
