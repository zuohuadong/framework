<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-17 20:39
 */
namespace Notadd\Extension;
use Notadd\Extension\Controllers\Admin\ExtensionController;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Symfony\Component\Finder\Finder;
/**
 * Class ExtensionServiceProvider
 * @package Notadd\Foundation\Extension
 */
class ExtensionServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $extension_dir = $this->app->make('extension')->getExtensionsDir();
        if($this->app->make('files')->isDirectory($extension_dir)) {
            foreach(Finder::create()->in($extension_dir)->directories()->depth(0) as $dir) {
                if(file_exists($file = $dir . '/bootstrap.php') && $this->setting->get('extension.' . $dir->getFilename() . '.enabled')) {
                    $extension = require $file;
                    $this->app->register($extension);
                }
            }
        }
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function() {
            $this->router->resource('extension', ExtensionController::class);
        });
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->bind('extension', ExtensionManager::class);
    }
}