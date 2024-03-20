<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude(['bootstrap', 'storage', 'vendor'])
    ->notPath('_ide_helper.php')
    ->name('*.php')
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12'                            => true,
        'array_syntax'                      => ['syntax' => 'short'],
        'binary_operator_spaces'            => ['operators' => ['=>' => 'align']],
        'no_unused_imports'                 => true,
        'ordered_imports'                   => ['sort_algorithm' => 'alpha'],
        'standardize_not_equals'            => true,
        'trailing_comma_in_multiline'       => ['elements' => ['arrays']],
        'trim_array_spaces'                 => true,
    ])
    ->setFinder($finder);
