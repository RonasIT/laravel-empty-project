<?php

namespace App\Console\Commands;

use Illuminate\Support\Carbon;
use Validator;
use Hash;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Yaml;
use App\Repositories\RoleRepository;

class Init extends Command
{
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
            $this->generateDotEnv(true);
        }

        Artisan::call('key:generate');

        if ($this->confirm('Do you want generate admin user?', true)) {
            $this->createAdminUser();
        }
    }

    public function generateDotEnv($isTestingConfig = false)
    {
        $connection = $this->choice('Please select database connection type', $this->connectionTypes, '1');

        $ymlSettings = Yaml::parse(file_get_contents(base_path('/') . 'docker-compose.yml'));
        $databaseSettings = $this->askDatabaseSettings($ymlSettings['services'][$connection], $connection);

        if (!$isTestingConfig) {
            $this->addSettingsToConfig($databaseSettings, $connection);
        }

        $exampleSettings = $this->generateExampleSettings($databaseSettings);
        $postfix = $isTestingConfig ? '.testing' : '';

        return file_put_contents(base_path('/') . '.env' . $postfix, $exampleSettings);
    }

    protected function askDatabaseSettings($defaultSettings, $connectionType)
    {
        $databaseSettings['DB_CONNECTION'] = $connectionType;
        $environment = $defaultSettings['environment'];

        foreach ($this->settings as $key => $question) {
            if ($key == 'DB_PORT') {
                $defaultSetting = substr($defaultSettings['ports'][0], -4);
            } else {
                $settingsName = array_get($this->dockerVariables[$connectionType], $key, false);
                $defaultSetting = ($settingsName) ? $environment[$settingsName] : '';
            }

            $databaseSettings[$key] = $this->ask($question, $defaultSetting);
        }

        return $databaseSettings;
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
        $data = view('ronasit::add_default_user')->with($admin)->render();
        $fileName = Carbon::now()->format('Y_m_d_His') . '_add_default_user.php';

        return file_put_contents("database/migrations/{$fileName}", "<?php\n\n{$data}");
    }
}
