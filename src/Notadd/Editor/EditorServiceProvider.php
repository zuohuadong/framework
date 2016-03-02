<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-10 17:21
 */
namespace Notadd\Editor;
use Illuminate\Support\ServiceProvider;
use Notadd\Editor\Controllers\UploadController;
use Notadd\Foundation\Traits\InjectRouterTrait;
/**
 * Class EditorServiceProvider
 * @package Notadd\Editor
 */
class EditorServiceProvider extends ServiceProvider {
    use InjectRouterTrait;
    /**
     * @return void
     */
    public function boot() {
        $this->getRouter()->resource('upload', UploadController::class);
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('editor', Editor::class);
        $this->app->singleton('editor.ueditor', '');
    }
}