<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 18:36
 */
namespace Notadd\Install\Controllers;
use Notadd\Foundation\Routing\Abstracts\AbstractController;
/**
 * Class InstallController
 * @package Notadd\Install\Controllers
 */
class InstallController extends AbstractController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        return $this->view('install::install');
    }
}