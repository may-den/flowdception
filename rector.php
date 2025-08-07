<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php53\Rector\Ternary\TernaryToElvisRector;
use Rector\Php70\Rector\If_\IfToSpaceshipRector;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\Php83\Rector\FuncCall\CombineHostPortLdapUriRector;
use Rector\Set\ValueObject\LevelSetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/',
    ])
    ->withImportNames(true, true, true, true)
    ->withSkip([
        IfToSpaceshipRector::class,
        TernaryToElvisRector::class,
        AddTypeToConstRector::class,
        CombineHostPortLdapUriRector::class,
        __DIR__ . '/vendor',
    ])
    ->withSets([LevelSetList::UP_TO_PHP_84])
    ->withParallel(240, 16, 10);
