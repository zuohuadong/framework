<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-10 1:16:45
 */
namespace Notadd\Page\Events;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Notadd\Page\Page;
/**
 * Class OnPageShow
 * @package Notadd\Page\Events
 */
class OnPageShow {
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    private $app;
    /**
     * @var \Notadd\Page\Page
     */
    private $page;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $view;
    /**
     * OnPageShow constructor.
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Contracts\View\Factory $view
     * @param \Notadd\Page\Page $page
     */
    public function __construct(Application $app, Factory $view, Page $page) {
        $this->app = $app;
        $this->page = $page;
        $this->view = $view;
    }
    /**
     * @return mixed
     */
    public function getPage() {
        return $this->page->getModel();
    }
    /**
     * @return \Notadd\Page\Page
     */
    public function getPageSelf() {
        return $this->page;
    }
    /**
     * @param \Notadd\Page\Page $page
     */
    public function setPage(Page $page) {
        $this->page = $page;
    }
    /**
     * @param $key
     * @param $value
     */
    public function share($key, $value) {
        $this->view->share($key, $value);
    }
}