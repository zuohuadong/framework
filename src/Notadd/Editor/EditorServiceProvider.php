<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-10 17:21
 */
namespace Notadd\Editor;
use Notadd\Editor\Controllers\UEditorController;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
/**
 * Class EditorServiceProvider
 * @package Notadd\Editor
 */
class EditorServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->router->any('ueditor', UEditorController::class . '@index');
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton('editor.ueditor', '');
    }
}