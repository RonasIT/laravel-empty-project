<?php

namespace App\Console\Commands;

use Yaml;
use Validator;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use App\Repositories\RoleRepository;

class Init extends Command
{
    const MYSQL_CONNECTION = 'mysql';
    const PGSQL_CONNECTION = 'pgsql';

    const MYSQL_TEST_HOST = 'mysql_test';
    const PGSQL_TEST_HOST = 'pgsql_test';

    protected $signature = 'init';

    protected $description = 'Command generate admin user and .env files';

    private $prevSettings;

    protected $settings = [
        'DB_HOST' => 'Please enter database connection host',
        'DB_PORT' => 'Please enter database connection port',
        'DB_DATABASE' => 'Please enter database name',
        'DB_USERNAME' => 'Please enter database user name',
        'DB_PASSWORD' => 'Please enter password'
    ];

    protected $dockerVariables = [
        'pgsql' => [
            'DB_PASSWORD' => 'POSTGRES_PASSWORD',
            'DB_USERNAME' => 'POSTGRES_USER',
            'DB_DATABASE' => 'POSTGRES_DB'
        ],
        'mysql' => [
            'DB_DATABASE' => 'MYSQL_DATABASE'
        ],
        'pgsql_test' => [
            'DB_PASSWORD' => 'POSTGRES_PASSWORD',
            'DB_USERNAME' => 'POSTGRES_USER',
            'DB_DATABASE' => 'POSTGRES_DB'
        ],
        'mysql_test' => [
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
        $connectionTypes = [
            self::MYSQL_CONNECTION,
            self::PGSQL_CONNECTION
        ];

        $connection = $this->choice('Please select database connection type', $connectionTypes, '1');

        $ymlSettings = Yaml::parse(file_get_contents('docker-compose.yml'));

        $settings = $this->askDatabaseEnvSettings($ymlSettings['services'], $connection);
        $settings['APP_ENV'] = 'local';
        $settings['DATA_COLLECTOR_KEY'] = $this->getDataCollectorKey();

        $this->addSettingsToConfig($settings, $connection);

        $this->prevSettings = $settings;

        $exampleSettings = $this->generateExampleSettings($settings);

        return file_put_contents('.env', $exampleSettings);
    }

    protected function askDatabaseEnvSettings($defaultSettings, $connectionType)
    {
        $databaseSettings['DB_CONNECTION'] = $connectionType;

        foreach ($this->settings as $key => $question) {
            $defaultSetting = $this->getDefaultSettingEnv(
                $key,
                $defaultSettings,
                $defaultSettings[$connectionType]['environment'],
                $connectionType
            );
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
            return $this->getPort($defaultSettings[$connectionType]['ports'][0]);
        }

        if ($key == 'DB_HOST') {
            $links = $defaultSettings['apache']['links'];

            if ($connectionType === self::MYSQL_CONNECTION) {
                return $links[1];
            }

            if ($connectionType === self::PGSQL_CONNECTION) {
                return $links[0];
            }
        }

        return '';
    }

    public function generateDotEnvDotTesting()
    {
        $connection = $this->prevSettings['DB_CONNECTION'];

        $ymlSettings = Yaml::parse(file_get_contents('docker-compose.yml'));

        $settings = $this->askDatabaseDotEnvTestingSettings($ymlSettings['services'], $connection);
        $settings['APP_ENV'] = 'testing';
        $settings['DATA_COLLECTOR_KEY'] = $this->prevSettings['DATA_COLLECTOR_KEY'];

        $exampleSettings = $this->generateExampleSettings($settings);

        return file_put_contents('.env.testing', $exampleSettings);
    }

    protected function askDatabaseDotEnvTestingSettings($defaultSettings, $connectionType)
    {
        $databaseSettings['DB_CONNECTION'] = $connectionType;
        $environment = $defaultSettings[$connectionType]['environment'];

        foreach ($this->settings as $key => $question) {
            $defaultSetting = $this->getDefaultSettingDotEnvTesting($key, $defaultSettings, $environment, $connectionType);
            $databaseSettings[$key] = $this->ask($question, $defaultSetting);
        }

        return $databaseSettings;
    }

    protected function getDefaultSettingDotEnvTesting($key, $defaultSettings, $environment, $connectionType)
    {
        if (array_get($this->dockerVariables[$connectionType], $key, false)) {
            $settingsName = array_get($this->dockerVariables[$connectionType], $key, false);

            return $environment[$settingsName];
        }

        if ($key == 'DB_PORT') {
            return $this->getPort($defaultSettings[$connectionType]['ports'][0]);
        }

        if ($key == 'DB_HOST') {
            if ($this->prevSettings['DB_CONNECTION'] === self::MYSQL_CONNECTION) {
                return self::MYSQL_TEST_HOST;
            }

            if ($this->prevSettings['DB_CONNECTION'] === self::PGSQL_CONNECTION) {
                return self::PGSQL_TEST_HOST;
            }        }

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

    private function getDataCollectorKey()
    {
        return $this->ask('Input new DATA_COLLECTOR_KEY', 'some-project-name');
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

    private function getPort($string)
    {
        $matches = preg_split('/:/', $string);

        return $matches[1];
    }
}
