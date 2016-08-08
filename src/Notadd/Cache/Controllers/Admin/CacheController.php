<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-27 21:30
 */
namespace Notadd\Cache\Controllers\Admin;
use Notadd\Foundation\Abstracts\AbstractAdminController;
use Notadd\Foundation\Console\Kernel;
/**
 * Class CacheController
 * @package Notadd\Cache\Controllers\Admin
 */
class CacheController extends AbstractAdminController {
    /**
     * @var \Notadd\Foundation\Console\Kernel
     */
    protected $artisan;
    /**
     * CacheController constructor.
     * @param \Notadd\Foundation\Console\Kernel $artisan
     */
    public function __construct(Kernel $artisan) {
        parent::__construct();
        $this->artisan = $artisan;
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        return $this->view('cache.index');
    }
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearCache() {
        $this->artisan->call('cache:clear');
        $this->artisan->call('static:clear');
        $this->artisan->call('view:clear');
        return $this->redirect->back();
    }
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearStatic() {
        $this->artisan->call('static:clear');
        return $this->redirect->back();
    }
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearView() {
        $this->artisan->call('view:clear');
        return $this->redirect->back();
    }
}