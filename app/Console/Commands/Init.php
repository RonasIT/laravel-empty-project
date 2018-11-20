<?php

namespace App\Console\Commands;

use Yaml;
use Validator;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Repositories\RoleRepository;

class Init extends Command
{
    private $prevSettings;

    protected $signature = 'init';

    protected $description = 'Command generate admin user and .env files';

    protected $settings = [
        'DB_HOST' => 'Please enter database connection host',
        'DB_PORT' => 'Please enter database connection port',
        'DB_DATABASE' => 'Please enter database name',
        'DB_USERNAME' => 'Please enter database user name',
        'DB_PASSWORD' => 'Please enter password'
    ];

    protected $connectionTypes = ['mysql', 'pgsql'];

    protected $dockerVariables = [
        'pgsql' => [
            'DB_PASSWORD' => 'POSTGRES_PASSWORD',
            'DB_USERNAME' => 'POSTGRES_USER',
            'DB_DATABASE' => 'POSTGRES_DB'
        ],
        'mysql' => [
            'DB_DATABASE' => 'MYSQL_DATABASE'
        ]
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        if ($this->confirm('Do you want generate .env?', true)) {
            $this->generateDotEnv();
        }

        if ($this->confirm('Do you want generate .env.testing?', true)) {
            $this->generateDotEnvDotTesting();
        }

        $this->call('key:generate');
        $this->call('jwt:secret');
        exec('php artisan jwt:secret -f --env=testing');

        if ($this->confirm('Do you want generate admin user?', true)) {
            $this->createAdminUser();
        }
    }

    public function generateDotEnv()
    {
        $connection = $this->choice('Please select database connection type', $this->connectionTypes, '1');

        $ymlSettings = Yaml::parse(file_get_contents(base_path('/') . 'docker-compose.yml'));

        $settings = $this->askDatabaseEnvSettings($ymlSettings['services'], $connection);
        $settings["APP_ENV"] = "local";

        $this->addSettingsToConfig($settings, $connection);
        $this->prevSettings = $settings;

        $exampleSettings = $this->generateExampleSettings($settings);

        return file_put_contents(base_path('/') . '.env', $exampleSettings);
    }

    protected function askDatabaseEnvSettings($defaultSettings, $connectionType)
    {
        $databaseSettings['DB_CONNECTION'] = $connectionType;

        foreach ($this->settings as $key => $question) {
            $defaultSetting = $this->getDefaultSettingEnv($key, $defaultSettings, $defaultSettings[$connectionType]['environment'], $connectionType);
            $databaseSettings[$key] = $this->ask($question, $defaultSetting);
        }

        return $databaseSettings;
    }

    protected function getDefaultSettingEnv($key, $defaultSettings, $environment, $connectionType)
    {
        if (array_get($this->dockerVariables[$connectionType], $key, false)) {
            $settingsName = array_get($this->dockerVariables[$connectionType], $key, false);

            return $environment[$settingsName];
        }

        if ($key == 'DB_PORT') {
            return substr($defaultSettings[$connectionType]['ports'][0], -4);
        }

        if ($key == 'DB_HOST') {
            $links = $defaultSettings['apache']['links'];
            $link = null;

            if ($connectionType === $this->connectionTypes[0]) {
                return $links[1];
            }

            if ($connectionType === $this->connectionTypes[1]) {
                return $links[0];
            }
        }

        return '';
    }

    public function generateDotEnvDotTesting()
    {
        $connection = $this->choice('Please select database connection type', $this->connectionTypes, '1');

        $ymlSettings = Yaml::parse(file_get_contents(base_path('/') . 'docker-compose.yml'));

        $settings = $this->askDatabaseDotEnvSettings($ymlSettings['services'][$connection], $connection);
        $settings["APP_ENV"] = "testing";

        $exampleSettings = $this->generateExampleSettings($settings);

        return file_put_contents(base_path('/') . '.env.testing', $exampleSettings);
    }

    protected function askDatabaseDotEnvSettings($defaultSettings, $connectionType)
    {
        $databaseSettings['DB_CONNECTION'] = $connectionType;
        $environment = $defaultSettings['environment'];

        foreach ($this->settings as $key => $question) {
            $defaultSetting = $this->getDefaultSettingDotEnv($key, $defaultSettings, $environment, $connectionType);
            $databaseSettings[$key] = $this->ask($question, $defaultSetting);
        }

        return $databaseSettings;
    }

    protected function getDefaultSettingDotEnv($key, $defaultSettings, $environment, $connectionType)
    {
        if (array_get($this->dockerVariables[$connectionType], $key, false)) {
            $settingsName = array_get($this->dockerVariables[$connectionType], $key, false);

            return $environment[$settingsName];
        }

        if ($key == 'DB_PORT') {
            return substr($defaultSettings['ports'][0], -4);
        }

        if($key == 'DB_HOST') {
            return $this->prevSettings['DB_HOST'];
        }

        return '';
    }

    protected function addSettingsToConfig($settings, $connectionType)
    {
        $configSettings['database.default'] = $connectionType;

        foreach ($settings as $key => $setting) {
            $settingName = strtolower(str_replace('DB_', '', $key));
            $configSettings["database.connections.{$connectionType}.{$settingName}"] = $setting;
        }

        config($configSettings);
    }

    protected function generateExampleSettings($settings)
    {
        $exampleContent = file_get_contents(base_path('/') . '.env.example');

        foreach ($settings as $type => $value) {
            $exampleContent = str_replace("{$type}=", "{$type}={$value}", $exampleContent);
        }

        return $exampleContent;
    }

    private function createAdminUser($data = [])
    {
        $data['password'] = $data['password'] ?? substr(md5(uniqid()), 0, 8);
        $data['name'] = $data['name'] ?? null;
        $data['email'] = $data['email'] ?? null;

        $admin['name'] = $this->ask('Please enter admin name', $data['name']);
        $admin['email'] = $this->ask('Please enter admin email', $data['email']);
        $admin['password'] = $this->ask('Please enter admin password', $data['password']);
        $admin['role'] = RoleRepository::ADMIN_ROLE;

        $validator = Validator::make($admin, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages());

            return $this->createAdminUser($admin);
        }

        return $this->publishMigration($admin);
    }

    public function publishMigration($admin)
    {
        $data = view('add_default_user')->with($admin)->render();
        $fileName = Carbon::now()->format('Y_m_d_His') . '_add_default_user.php';

        return file_put_contents("database/migrations/{$fileName}", "<?php\n\n{$data}");
    }
}
