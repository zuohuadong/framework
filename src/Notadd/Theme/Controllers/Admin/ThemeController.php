<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Theme\Controllers\Admin;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Notadd\Admin\Controllers\AbstractAdminController;
use Notadd\Foundation\SearchEngine\Optimization;
use Notadd\Setting\Factory;
/**
 * Class ThemeController
 * @package Notadd\Theme\Controllers\Admin
 */
class ThemeController extends AbstractAdminController {
    /**
     * @var \Notadd\Theme\Factory
     */
    protected $theme;
    /**
     * ThemeController constructor.
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Events\Dispatcher $events
     * @param \Illuminate\Contracts\Logging\Log $log
     * @param \Illuminate\Routing\Redirector $redirect
     * @param \Illuminate\Http\Request $request
     * @param \Notadd\Setting\Factory $setting
     * @param \Notadd\Foundation\SearchEngine\Optimization $seo
     * @param \Illuminate\Contracts\View\Factory $view
     */
    public function __construct(Application $app, Dispatcher $events, Log $log, Redirector $redirect, Request $request, Factory $setting, Optimization $seo, ViewFactory $view) {
        parent::__construct($app, $events, $log, $redirect, $request, $setting, $seo, $view);
        $this->theme = $this->app->make('theme');
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $themes = $this->theme->getThemeList();
        $this->share('themes', $themes);
        return $this->view('theme.index');
    }
    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id) {
        $themes = $this->theme->getThemeList();
        if($themes->has($id)) {
            if($id != $this->setting->get('site.theme')) {
                $this->setting->set('site.theme', $id);
            }
        }
        return $this->redirect->to('admin/theme');
    }
}