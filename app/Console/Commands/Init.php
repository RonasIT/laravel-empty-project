<?php

namespace App\Console\Commands;

use Validator;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

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

    public function generateDotEnv($isTestingConfig = false) {
        $connectionsTypes = array_keys(config('database.connections'));

        $database['DB_CONNECTION'] = $this->choice('Please select database connection type', $connectionsTypes, '2');
        $database['DB_HOST'] = $this->ask('Please enter database connection host');
        $database['DB_PORT'] = $this->ask('Please enter database connection port');
        $database['DB_DATABASE'] = $this->ask('Please enter database name');
        $database['DB_USERNAME'] = $this->ask('Please enter database username');
        $database['DB_PASSWORD'] = $this->ask('Please enter database password');

        $exampleContent = file_get_contents(base_path('/') . '.env.example');

        foreach ($database as $type => $value) {
            $exampleContent = str_replace($type . '=', ($type ? $type : '') . '=' . $value, $exampleContent);
        }

        $postfix = $isTestingConfig ? 'testing' : '';

        return file_put_contents(base_path('/') . '.env' . $postfix, $exampleContent);
    }

    private function createAdminUser($data = []) {
        $admin['name'] = $this->ask('Please enter admin name', (empty($data)) ? null : $data['name']);
        $admin['email'] = $this->ask('Please enter admin email', (empty($data)) ? null : $data['email']);
        $admin['password'] = $this->ask('Please enter admin password', (empty($data)) ? null : $data['password']);

        $validator = Validator::make($admin, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            print_r($validator->messages());

            return $this->createAdminUser($admin);
        }

        $service = new UserService();
        $admin = $service->create($admin);

        return $admin;
    }
}
