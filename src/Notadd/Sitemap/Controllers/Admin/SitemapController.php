<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-17 12:11
 */
namespace Notadd\Sitemap\Controllers\Admin;
use Notadd\Foundation\Abstracts\AbstractAdminController;
/**
 * Class SitemapController
 * @package Notadd\Sitemap\Controllers\Admin
 */
class SitemapController extends AbstractAdminController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        return $this->view('sitemap.index');
    }
}