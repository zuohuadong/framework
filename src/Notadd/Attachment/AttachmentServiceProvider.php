<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-11 12:26
 */
namespace Notadd\Attachment;
use Illuminate\Support\ServiceProvider;
use Notadd\Attachment\Controllers\Admin\AttachmentController as AdminAttachmentController;
use Notadd\Attachment\Controllers\Admin\Configurations\FormatController as AdminFormatController;
use Notadd\Attachment\Controllers\Admin\Configurations\SizeController as AdminSizeController;
use Notadd\Attachment\Controllers\Admin\Configurations\UploadController as AdminUploadController;
use Notadd\Attachment\Controllers\Admin\ListController as AdminListController;
use Notadd\Foundation\Traits\InjectEventsTrait;
use Notadd\Foundation\Traits\InjectRouterTrait;
/**
 * Class AttachmentServiceProvider
 * @package Notadd\Attachment
 */
class AttachmentServiceProvider extends ServiceProvider {
    use InjectEventsTrait, InjectRouterTrait;
    /**
     * @return void
     */
    public function boot() {
        $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function() {
            $this->getRouter()->resource('attachment/list', AdminListController::class);
            $this->getRouter()->resource('attachment/format', AdminFormatController::class);
            $this->getRouter()->resource('attachment/size', AdminSizeController::class);
            $this->getRouter()->resource('attachment/upload', AdminUploadController::class);
            $this->getRouter()->resource('attachment', AdminAttachmentController::class);
        });
    }
    /**
     * @return void
     */
    public function register() {
    }
}