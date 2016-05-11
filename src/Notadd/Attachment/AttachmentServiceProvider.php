<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-11 12:26
 */
namespace Notadd\Attachment;
use Illuminate\Support\ServiceProvider;
use Notadd\Attachment\Controllers\Admin\AttachmentController;
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
            $this->getRouter()->resource('attachment', AttachmentController::class);
        });
    }
    /**
     * @return void
     */
    public function register() {
    }
}