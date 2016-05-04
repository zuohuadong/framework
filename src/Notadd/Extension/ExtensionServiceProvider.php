<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-17 20:39
 */
namespace Notadd\Extension;
use Illuminate\Support\ServiceProvider;
use Notadd\Extension\Controllers\Admin\ExtensionController;
use Notadd\Foundation\Traits\InjectExtensionTrait;
use Notadd\Foundation\Traits\InjectRouterTrait;
use Notadd\Foundation\Traits\InjectSettingTrait;
use Symfony\Component\Finder\Finder;
/**
 * Class ExtensionServiceProvider
 * @package Notadd\Foundation\Extension
 */
class ExtensionServiceProvider extends ServiceProvider {
    use InjectExtensionTrait, InjectRouterTrait, InjectSettingTrait;
    /**
     * @return void
     */
    public function boot() {
        $extension_dir = $this->getExtension()->getExtensionsDir();
        if($this->app->make('files')->isDirectory($extension_dir)) {
            foreach(Finder::create()->in($extension_dir)->directories()->depth(0) as $dir) {
                if(file_exists($file = $dir . '/bootstrap.php') && $this->getSetting()->get('extension.' . $dir->getFilename() . '.enabled')) {
                    $extension = require $file;
                    $this->app->register($extension);
                }
            }
        }
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function() {
            $this->getRouter()->resource('extension', ExtensionController::class);
        });
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->bind('extension', ExtensionManager::class);
    }
}