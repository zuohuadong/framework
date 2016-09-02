<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-31 16:03
 */
namespace Notadd\Setting;
use Illuminate\Database\ConnectionInterface;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Notadd\Setting\Contracts\SettingsRepository;
/**
 * Class SettingServiceProvider
 * @package Notadd\Setting
 */
class SettingServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function register() {
        $this->app->alias(SettingsRepository::class, 'setting');
        $this->app->singleton(SettingsRepository::class, function() {
            return new MemoryCacheSettingsRepository(
                new DatabaseSettingsRepository(
                    $this->app->make(ConnectionInterface::class)
                )
            );
        });
    }
}