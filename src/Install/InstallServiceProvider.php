<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 16:31
 */
namespace Notadd\Install;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Notadd\Install\Contracts\PrerequisiteContract;
use Notadd\Install\Listeners\RouteRegister;
use Notadd\Install\Prerequisite\PhpExtension;
use Notadd\Install\Prerequisite\PhpVersion;
use Notadd\Install\Prerequisite\WritablePath;
/**
 * Class InstallServiceProvider
 * @package Notadd\Install
 */
class InstallServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->events->subscribe(RouteRegister::class);
        $this->loadViewsFrom(realpath(__DIR__ . '/../../views/install'), 'install');
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->bind(PrerequisiteContract::class, function () {
            return new Composite(
                new PhpVersion('5.5.0'),
                new PhpExtension([
                    'dom',
                    'fileinfo',
                    'gd',
                    'json',
                    'mbstring',
                    'openssl',
                    'pdo_mysql',
                ]),
                new WritablePath([
                    public_path(),
                    storage_path()
                ])
            );
        });
    }
}