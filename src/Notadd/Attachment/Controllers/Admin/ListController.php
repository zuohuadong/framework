<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-11 17:45
 */
namespace Notadd\Attachment\Controllers\Admin;
use Notadd\Admin\Controllers\AbstractAdminController;
/**
 * Class ListController
 * @package Notadd\Attachment\Controllers\Admin
 */
class ListController extends AbstractAdminController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        return $this->view('attachment.list');
    }
}