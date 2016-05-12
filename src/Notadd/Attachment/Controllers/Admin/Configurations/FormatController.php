<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-12 15:05
 */
namespace Notadd\Attachment\Controllers\Admin\Configurations;
use Illuminate\Http\Request;
use Notadd\Admin\Controllers\AbstractAdminController;
/**
 * Class FormatController
 * @package Notadd\Attachment\Controllers\Admin\Configurations
 */
class FormatController extends AbstractAdminController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->share('allow_image_format', $this->setting->get('attachment.format.allow.image'));
        $this->share('allow_catcher_format', $this->setting->get('attachment.format.allow.catcher'));
        $this->share('allow_video_format', $this->setting->get('attachment.format.allow.video'));
        $this->share('allow_file_format', $this->setting->get('attachment.format.allow.file'));
        $this->share('allow_manager_image_format', $this->setting->get('attachment.format.allow.manager.image'));
        $this->share('allow_manager_file_format', $this->setting->get('attachment.format.allow.manager.file'));
        return $this->view('attachment.configuration.format');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $this->setting->set('attachment.format.allow.image', $request->get('allow_image_format'));
        $this->setting->set('attachment.format.allow.catcher', $request->get('allow_catcher_format'));
        $this->setting->set('attachment.format.allow.video', $request->get('allow_video_format'));
        $this->setting->set('attachment.format.allow.file', $request->get('allow_file_format'));
        $this->setting->set('attachment.format.allow.manager.image', $request->get('allow_manager_image_format'));
        $this->setting->set('attachment.format.allow.manager.file', $request->get('allow_manager_file_format'));
        return $this->redirect->back();
    }
}