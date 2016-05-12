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
        return $this->view('attachment.configuration.format');
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        return $this->redirect->back();
    }
}