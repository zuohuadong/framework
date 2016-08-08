<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-11 12:29
 */
namespace Notadd\Attachment\Controllers\Admin;
use Notadd\Foundation\Abstracts\AbstractAdminController;
/**
 * Class AttachmentController
 * @package Notadd\Attachment\Controllers\Admin
 */
class AttachmentController extends AbstractAdminController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        return $this->view('attachment.index');
    }
}