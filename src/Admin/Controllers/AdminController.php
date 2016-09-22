<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-29 16:21
 */
namespace Notadd\Admin\Controllers;
use Notadd\Foundation\Routing\Abstracts\AbstractController;
/**
 * Class AdminController
 * @package Notadd\Admin\Controllers
 */
class AdminController extends AbstractController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        return $this->view('admin::index');
    }
}