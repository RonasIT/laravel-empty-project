<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude(['docker', 'k8s', 'vendor', 'bootstrap/cache'])
    ->notPath(['_ide_helper.php', '_ide_helper_models.php'])
    ->in(__DIR__);

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder);
