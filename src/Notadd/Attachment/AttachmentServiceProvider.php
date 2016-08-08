<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-11 12:26
 */
namespace Notadd\Attachment;
use Notadd\Attachment\Controllers\Admin\AttachmentController as AdminAttachmentController;
use Notadd\Attachment\Controllers\Admin\ConfigurationController as AdminConfigurationController;
use Notadd\Attachment\Controllers\Admin\ListController as AdminListController;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
/**
 * Class AttachmentServiceProvider
 * @package Notadd\Attachment
 */
class AttachmentServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function() {
            $this->router->resource('attachment/list', AdminListController::class);
            $this->router->resource('attachment/configuration', AdminConfigurationController::class);
            $this->router->resource('attachment', AdminAttachmentController::class);
        });
    }
    /**
     * @return void
     */
    public function register() {
    }
}