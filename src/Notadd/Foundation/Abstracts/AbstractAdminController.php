<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-29 22:45
 */
namespace Notadd\Foundation\Abstracts;
use Illuminate\Support\Str;
/**
 * Class AbstractAdminController
 * @package Notadd\Admin\Controllers
 */
class AbstractAdminController extends AbstractController {
    /**
     * @var \Illuminate\Session\SessionManager
     */
    protected $session;
    /**
     * AbstractAdminController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->session = $this->app->make('session');
        $this->share('admin_theme', $this->app->make('request')->cookie('admin-theme'));
    }
    /**
     * @param $template
     * @return \Illuminate\Contracts\View\View
     */
    protected function view($template) {
        if(Str::contains($template, '::')) {
            return $this->view->make($template);
        } else {
            return $this->view->make('admin::' . $template);
        }
    }
}