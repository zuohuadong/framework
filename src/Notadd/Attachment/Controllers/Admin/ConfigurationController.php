<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-12 15:04
 */
namespace Notadd\Attachment\Controllers\Admin;
use Illuminate\Http\Request;
use Notadd\Admin\Controllers\AbstractAdminController;
/**
 * Class UploadController
 * @package Notadd\Attachment\Controllers\Admin\Configurations
 */
class ConfigurationController extends AbstractAdminController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->share('engine', $this->setting->get('attachment.engine'));
        $this->share('size_file_limit', $this->setting->get('attachment.size.file.limit'));
        $this->share('size_image_limit', $this->setting->get('attachment.size.image.limit'));
        $this->share('size_video_limit', $this->setting->get('attachment.size.video.limit'));
        $this->share('allow_image_format', $this->setting->get('attachment.format.allow.image'));
        $this->share('allow_catcher_format', $this->setting->get('attachment.format.allow.catcher'));
        $this->share('allow_video_format', $this->setting->get('attachment.format.allow.video'));
        $this->share('allow_file_format', $this->setting->get('attachment.format.allow.file'));
        $this->share('allow_manager_image_format', $this->setting->get('attachment.format.allow.manager.image'));
        $this->share('allow_manager_file_format', $this->setting->get('attachment.format.allow.manager.file'));
        return $this->view('attachment.configuration');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $this->setting->set('attachment.engine', $request->get('engine'));
        $this->setting->set('attachment.size.file.limit', $request->get('size_file_limit'));
        $this->setting->set('attachment.size.image.limit', $request->get('size_image_limit'));
        $this->setting->set('attachment.size.video.limit', $request->get('size_video_limit'));
        $this->setting->set('attachment.format.allow.image', $request->get('allow_image_format'));
        $this->setting->set('attachment.format.allow.catcher', $request->get('allow_catcher_format'));
        $this->setting->set('attachment.format.allow.video', $request->get('allow_video_format'));
        $this->setting->set('attachment.format.allow.file', $request->get('allow_file_format'));
        $this->setting->set('attachment.format.allow.manager.image', $request->get('allow_manager_image_format'));
        $this->setting->set('attachment.format.allow.manager.file', $request->get('allow_manager_file_format'));
        return $this->redirect->back();
    }
}