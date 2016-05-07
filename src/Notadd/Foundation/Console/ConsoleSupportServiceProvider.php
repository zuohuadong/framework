<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-01 00:07
 */
namespace Notadd\Foundation\Console;
use Illuminate\Console\ScheduleServiceProvider;
use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Database\SeedServiceProvider;
use Illuminate\Support\AggregateServiceProvider;
use Notadd\Foundation\Composer\ComposerServiceProvider;
use Notadd\Foundation\Queue\ConsoleServiceProvider as QueueConsoleServiceProvider;
use Notadd\Foundation\Session\ConsoleServiceProvider as SessionConsoleServiceProvider;
/**
 * Class ConsoleSupportServiceProvider
 * @package Notadd\Foundation\Console
 */
class ConsoleSupportServiceProvider extends AggregateServiceProvider {
    /**
     * @var bool
     */
    protected $defer = true;
    /**
     * @var array
     */
    protected $providers = [
        ScheduleServiceProvider::class,
        ComposerServiceProvider::class,
        MigrationServiceProvider::class,
        SeedServiceProvider::class,
        QueueConsoleServiceProvider::class,
        SessionConsoleServiceProvider::class,
    ];
}