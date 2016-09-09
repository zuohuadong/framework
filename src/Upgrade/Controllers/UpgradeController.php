<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-09 17:30
 */
namespace Notadd\Upgrade\Controllers;
use Notadd\Foundation\Http\Abstracts\AbstractController;
/**
 * Class UpgradeController
 * @package Notadd\Upgrade\Controllers
 */
class UpgradeController extends AbstractController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        return $this->view('install::upgrade');
    }
}