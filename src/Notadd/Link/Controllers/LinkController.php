<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 13:47
 */
namespace Notadd\Link\Controllers;
use Notadd\Foundation\Routing\Controller;
use Notadd\Link\Models\Link;
/**
 * Class LinkController
 * @package Notadd\Link\Controllers
 */
class LinkController extends Controller {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->share('list', Link::whereIsEnabled(true)->get());
        return $this->view('link.index');
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id) {
        return $this->view('');
    }
}