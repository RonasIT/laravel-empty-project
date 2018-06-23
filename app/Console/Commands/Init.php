<?php

namespace App\Console\Commands;

use Validator;
use Hash;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Yaml;

class Init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command generate admin user and .env files';

    protected $settings = [
        'DB_HOST' => 'Please enter database connection host',
        'DB_PORT' => 'Please enter database connection port',
        'DB_DATABASE' => 'Please enter database name',
        'DB_USERNAME' => 'Please enter database user name',
        'DB_PASSWORD' => 'Please enter password'
    ];

    protected $abbreviations = [
        'pgsql' => [
            'DB_PASSWORD' => 'POSTGRES_PASSWORD',
            'DB_USER' => 'POSTGRES_USER',
            'DB_DATABASE' => 'POSTGRES_DB'
        ],
        'mysql' => [
            'DB_DATABASE' => 'MYSQL_DATABASE'
        ]

    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *function
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm('Do you want generate .env?')) {
            $this->generateDotEnv();
        }

        if ($this->confirm('Do you want generate .env.testing?')) {
            $this->generateDotEnv(true);
        }

        if ($this->confirm('Do you want generate admin user?')) {
            $this->createAdminUser();
        }

        Artisan::call('key:generate');
    }

    public function generateDotEnv($isTestingConfig = false)
    {
        $connectionsTypes = array_keys(config('database.connections'));
        $connection = $this->choice('Please select database connection type', $connectionsTypes, '2');

        $ymlSettings = Yaml::parse(file_get_contents(base_path('/') . 'docker-compose.yml'));
        $settings = $this->askDatabaseSettings($ymlSettings['services'], $connection);

        if (!$isTestingConfig) {
            $this->addSettingsToConfig($settings, $connection);
        }

        $exampleSettings = $this->generateExampleSettings($settings);
        $postfix = $isTestingConfig ? 'testing' : '';

        return file_put_contents(base_path('/') . '.env' . $postfix, $exampleSettings);
    }

    protected function askDatabaseSettings($defaultSettings, $connectionType)
    {
        $databaseSettings['DB_CONNECTION'] = $connectionType;

        foreach ($this->settings as $key => $question) {
            $settingsName = (!empty($this->abbreviations[$connectionType][$key])) ?? $this->abbreviations[$connectionType];
            $defaultSetting = ($settingsName) ? $defaultSettings[$connectionType][$settingsName] : '';
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
    }

    private function createAdminUser($data = [])
    {
        $data['password'] = $data['password'] ?? Hash::make(str_random(8));
        $data['name'] = $data['name'] ?? null;
        $data['email'] = $data['email'] ?? null;

        $admin['name'] = $this->ask('Please enter admin name', $data['name']);
        $admin['email'] = $this->ask('Please enter admin email', $data['email']);
        $admin['password'] = $this->ask('Please enter admin password', $data['password']);

        $validator = Validator::make($admin, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            $this->error($validator->messages());

            return $this->createAdminUser($admin);
        }

        $service = new UserService();
        $admin = $service->create($admin);

        return $admin;
    }
}
