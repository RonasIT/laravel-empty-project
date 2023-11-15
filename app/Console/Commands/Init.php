<?php

namespace App\Console\Commands;

use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Init extends Command
{
    const TEMPLATES_PATH = '.templates';

    const RESOURCES_ITEMS = [
        'issue_tracker' => 'Issue Tracker',
        'figma' => 'Figma',
        'sentry' => 'Sentry',
        'datadog' => 'DataDog',
        'argocd' => 'ArgoCD',
        'telescope' => 'Laravel Telescope',
    ];

    const CONTACTS_ITEMS = [
        'manager' => 'Manager',
        'team_lead' => 'Code Owner/Team Lead',
    ];

    protected $signature = 'init {application-name : The application name }';

    protected $description = 'Initialize required project parameters to run DEV environment';

    protected array $adminCredentials = [];

    protected string $appUrl;

    protected array $emptyValuesList = [];

    protected string $readmeContent = '';

    public function handle(): void
    {
        $appName = $this->argument('application-name');
        $kebabName = Str::kebab($appName);

        $this->appUrl = $this->ask('Please enter an application URL', "https://api.dev.{$kebabName}.com");

        $this->updateConfigFile('.env.testing', '=', [
            'DATA_COLLECTOR_KEY' => "{$kebabName}-local"
        ]);

        $envFile = (file_exists('.env'))
            ? '.env'
            : '.env.example';

        $this->updateConfigFile($envFile, '=', [
            'APP_NAME' => $appName,
            'SWAGGER_REMOTE_DRIVER_KEY' => "{$kebabName}-local"
        ]);

        $this->updateConfigFile('.env.development', '=', [
            'APP_NAME' => $appName,
            'DATA_COLLECTOR_KEY' => $kebabName,
            'APP_URL' => $this->appUrl,
        ]);

        $this->updateConfigFile('.env.ci-testing', '=', [
            'DATA_COLLECTOR_KEY' => "{$kebabName}"
        ]);

        $this->info('Project initialized successfully!');

        if ($this->confirm('Do you want to generate an admin user?', true)) {
            $this->createAdminUser($kebabName);
        }

        if ($this->confirm('Do you want to generate a README file?', true)) {
            $this->fillReadme();

            if ($this->confirm('Do you need a `Resources & Contacts` part?', true)) {
                $this->fillResourcesAndContacts();
                $this->fillResources();
                $this->fillContacts();
            }

            if ($this->confirm('Do you need a `Prerequisites` part?', true)) {
                $this->fillPrerequisites();
            }

            if ($this->confirm('Do you need a `Getting Started` part?', true)) {
                $this->fillGettingStarted();
            }

            if ($this->confirm('Do you need an `Environments` part?', true)) {
                $this->fillEnvironments();
            }

            if ($this->confirm('Do you need a `Credentials and Access` part?', true)) {
                $this->fillCredentialsAndAccess();
            }

            $this->saveReadme();

            $this->info('README generated successfully!');

            if ($this->emptyValuesList) {
                $this->warn('Don`t forget to fill the following empty values:');

                foreach ($this->emptyValuesList as $value) {
                    $this->warn("- {$value}");
                }
            }
        }
    }

    protected function createAdminUser($kebabName): void
    {
        $defaultPassword = substr(md5(uniqid()), 0, 8);

        $this->adminCredentials = [
            'name' => $this->ask('Please enter an admin name', 'Admin'),
            'email' => $this->ask('Please enter an admin email', "admin@{$kebabName}.com"),
            'password' => $this->ask('Please enter an admin password', $defaultPassword),
            'role_id' => Role::ADMIN
        ];

        $this->publishMigration();
    }

    protected function fillReadme(): void
    {
        $appName = $this->argument('application-name');
        $file = $this->loadReadmePart('README.md');

        $this->setReadmeValue($file, 'project_name', $appName);

        $this->readmeContent = $file;
    }

    protected function fillResourcesAndContacts(): void
    {
        $filePart = $this->loadReadmePart('RESOURCES_AND_CONTACTS.md');

        $this->updateReadmeFile($filePart);
    }

    protected function fillResources(): void
    {
        $filePart = $this->loadReadmePart('RESOURCES.md');

        foreach (self::RESOURCES_ITEMS as $key => $title) {
            if ($this->confirm("Are you going to use {$title}?", true)) {
                $defaultLink = ($key === 'telescope') ? $this->appUrl . '/telescope' : '';

                if ($link = $this->ask("Please enter a {$title} link", $defaultLink)) {
                    $this->setReadmeValue($filePart, "{$key}_link", $link);
                } else {
                    $this->emptyValuesList[] = "{$title} link";
                }

                $this->removeTag($filePart, $key);
            } else {
                $this->removeStringByTag($filePart, $key);
            }
        }

        $this->setReadmeValue($filePart, 'api_link', $this->appUrl);
        $this->updateReadmeFile($filePart);
    }

    protected function fillContacts(): void
    {
        $filePart = $this->loadReadmePart('CONTACTS.md');

        foreach (self::CONTACTS_ITEMS as $key => $title) {
            if ($link = $this->ask("Please enter a {$title} contact", '')) {
                $this->setReadmeValue($filePart, "{$key}_link", $link);
            } else {
                $this->emptyValuesList[] = "{$title} contact";
            }

            $this->removeTag($filePart, $key);
        }

        $this->updateReadmeFile($filePart);
    }

    protected function fillPrerequisites(): void
    {
        $filePart = $this->loadReadmePart('PREREQUISITES.md');

        $this->updateReadmeFile($filePart);
    }

    protected function fillGettingStarted(): void
    {
        $gitProjectPath = trim((string) shell_exec('git ls-remote --get-url origin'));
        $filePart = $this->loadReadmePart('GETTING_STARTED.md');

        $this->setReadmeValue($filePart, 'git_project_path', $gitProjectPath);
        $this->updateReadmeFile($filePart);
    }

    protected function fillEnvironments(): void
    {
        $filePart = $this->loadReadmePart('ENVIRONMENTS.md');

        $this->setReadmeValue($filePart, 'api_link', $this->appUrl);
        $this->updateReadmeFile($filePart);
    }

    protected function fillCredentialsAndAccess(): void
    {
        $filePart = $this->loadReadmePart('CREDENTIALS_AND_ACCESS.md');

        if ($this->adminCredentials) {
            $this->setReadmeValue($filePart, 'admin_email', $this->adminCredentials['email']);
            $this->setReadmeValue($filePart, 'admin_password', $this->adminCredentials['password']);
            $this->removeTag($filePart, 'admin_credentials');
        } else {
            $this->removeStringByTag($filePart, 'admin_credentials');
        }

        $this->updateReadmeFile($filePart);
    }

    protected function addQuotes($string): string
    {
        return (Str::contains($string, ' ')) ? "\"{$string}\"" : $string;
    }

    protected function publishMigration(): void
    {
        $data = view('add_default_user')->with($this->adminCredentials)->render();
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

    protected function loadReadmePart(string $fileName): string
    {
        return file_get_contents(self::TEMPLATES_PATH . DIRECTORY_SEPARATOR . $fileName);
    }

    protected function updateReadmeFile(string $filePart): void
    {
        $filePart = preg_replace('#(\n){3,}#', "\n", $filePart);

        $this->readmeContent .= "\n" . $filePart;
    }

    protected function removeStringByTag(string &$text, string $tag): void
    {
        $text = preg_replace("#({{$tag}.*?}).*?({/{$tag}})#", '', $text);
    }

    protected function removeTag(string &$text, string $tag): void
    {
        $text = preg_replace("#{(/*){$tag}}#", '', $text);
    }

    protected function setReadmeValue(string &$file, string $key, string $value): void
    {
        $file = str_replace(":{$key}", $value, $file);
    }

    protected function saveReadme(): void
    {
        file_put_contents('README.md', $this->readmeContent);
    }
}
