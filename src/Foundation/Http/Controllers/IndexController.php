<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-26 14:41
 */
namespace Notadd\Foundation\Http\Controllers;
use Notadd\Foundation\Http\Abstracts\AbstractController;
/**
 * Class IndexController
 * @package Notadd\Foundation\Http\Controllers
 */
class IndexController extends AbstractController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        return $this->view('index');
    }
}