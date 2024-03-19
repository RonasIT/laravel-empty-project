<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 18.10.16
 * Time: 10:30
 */

return [
    'paths' => [
        'models' => 'app/Models',
        'services' => 'app/Services',
        'requests' => 'app/Http/Requests',
        'controllers' => 'app/Http/Controllers',
        'migrations' => 'database/migrations',
        'repositories' => 'app/Repositories',
        'tests' => 'tests',
        'routes' => 'routes/api.php',
        'factory' => 'database/factories/ModelFactory.php',
        'seeds' => 'database/seeds',
        'database_seeder' => 'database/seeds/DatabaseSeeder.php',
        'translations' => 'resources/lang/en/validation.php',
     ],
    'stubs' => [
        'model' => 'entity-generator::model',
        'relation' => 'entity-generator::relation',
        'repository' => 'entity-generator::repository',
        'service' => 'entity-generator::service',
        'service_with_trait' => 'entity-generator::service_with_trait',
        'controller' => 'entity-generator::controller',
        'request' => 'entity-generator::request',
        'routes' => 'entity-generator::routes',
        'use_routes' => 'entity-generator::use_routes',
        'factory' => 'entity-generator::factory',
        'migration' => 'entity-generator::migration',
        'dump' => 'entity-generator::dumps.pgsql',
        'test' => 'entity-generator::test',
        'empty_factory' => 'entity-generator::empty_factory',
        'translation_not_found' => 'entity-generator::translation_not_found',
        'validation' => 'entity-generator::validation',
        'seeder' => 'entity-generator::seeder',
        'database_empty_seeder' => 'entity-generator::database_seed_empty',
    ],
];
