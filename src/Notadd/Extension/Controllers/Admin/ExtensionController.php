<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-03 17:00
 */
namespace Notadd\Extension\Controllers\Admin;
use Notadd\Admin\Controllers\AbstractAdminController;
/**
 * Class ExtensionController
 * @package Notadd\Extension\Controllers\Admin
 */
class ExtensionController extends AbstractAdminController {
    /**
     * ExtensionController constructor.
     */
    public function __construct() {
        parent::__construct();
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        return $this->view('admin::extension.index');
    }
}