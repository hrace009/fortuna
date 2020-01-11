<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenTraits;
use NunoMaduro\PhpInsights\Domain\Metrics\Architecture\Classes;
use SlevomatCodingStandard\Sniffs\Commenting\EmptyCommentSniff;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenFinalClasses;
use SlevomatCodingStandard\Sniffs\Functions\UnusedParameterSniff;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenPrivateMethods;
use ObjectCalisthenics\Sniffs\Classes\ForbiddenPublicPropertySniff;
use NunoMaduro\PhpInsights\Domain\Insights\ForbiddenDefineFunctions;
use SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\TypeHintDeclarationSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\AlphabeticallySortedUsesSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowShortTernaryOperatorSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\CodeAnalysis\UselessOverridingMethodSniff;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Preset
    |--------------------------------------------------------------------------
    |
    | This option controls the default preset that will be used by PHP Insights
    | to make your code reliable, simple, and clean. However, you can always
    | adjust the `Metrics` and `Insights` below in this configuration file.
    |
    | Supported: "default", "laravel", "symfony", "magento2", "drupal"
    |
    */

    'preset' => 'laravel',

    /*
   |--------------------------------------------------------------------------
   | IDE
   |--------------------------------------------------------------------------
   |
   | This options allow to add hyperlinks in your terminal to quickly open
   | files in your favorite IDE while browsing your PhpInsights report.
   |
   | Supported: "textmate", "macvim", "emacs", "sublime", "phpstorm",
   | "atom", "vscode".
   |
   | If you have another IDE that is not in this list but which provide an
   | url-handler, you could fill this config with a pattern like this:
   |
   | myide://open?url=file://%f&line=%l
   |
   */
    'ide' => 'phpstorm',

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may adjust all the various `Insights` that will be used by PHP
    | Insights. You can either add, remove or configure `Insights`. Keep in
    | mind that all added `Insights` must belong to a specific `Metric`.
    |
    */

    'exclude' => [
        'app/Http/Notifications/*.php',
        'app/Rules/*.php',
    ],

    'add' => [
        Classes::class => [
            ForbiddenFinalClasses::class,
        ],
    ],

    'remove' => [
        AlphabeticallySortedUsesSniff::class,
        DeclareStrictTypesSniff::class,
        DisallowMixedTypeHintSniff::class,
        ForbiddenDefineFunctions::class,
        ForbiddenNormalClasses::class,
        ForbiddenTraits::class,
        TypeHintDeclarationSniff::class,
        DisallowShortTernaryOperatorSniff::class,
        ForbiddenPublicPropertySniff::class,
        UselessOverridingMethodSniff::class,
        EmptyCommentSniff::class,
    ],

    'config' => [
        ForbiddenPrivateMethods::class => [
            'title' => 'The usage of private methods is not idiomatic in Laravel.',
        ],
        UnusedParameterSniff::class => [
            'exclude' => [
                'app/Http/Notifications/*.php',
                'app/Rules/*.php',
            ],
        ],
    ],
];
