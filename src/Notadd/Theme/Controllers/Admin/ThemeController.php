<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Theme\Controllers\Admin;
use Notadd\Admin\Controllers\AbstractAdminController;
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
     */
    public function __construct() {
        parent::__construct();
        $this->theme = $this->app->make('theme');
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $themes = $this->theme->getThemeList()->toArray();
        unset($themes['admin']);
        if($this->session->get('message')) {
            $message = explode(PHP_EOL, $this->session->get('message'));
        } else {
            $message = $this->session->get('message');
        }
        $this->share('message', $message);
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