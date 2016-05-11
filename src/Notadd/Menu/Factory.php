<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 15:13
 */
namespace Notadd\Menu;
use Illuminate\Contracts\Foundation\Application;
use Notadd\Menu\Models\Menu;
use Notadd\Menu\Models\MenuGroup;
/**
 * Class Factory
 * @package Notadd\Menu
 */
class Factory {
    /**
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $application;
    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;
    /**
     * Factory constructor.
     * @param \Illuminate\Contracts\Foundation\Application $application
     * @param \Illuminate\Contracts\View\Factory $view
     */
    public function __construct(Application $application, \Illuminate\Contracts\View\Factory $view) {
        $this->application = $application;
        $this->view = $view;
    }
    /**
     * @param $group_id
     * @return array
     */
    public function build($group_id) {
        $menus = [];
        Menu::buildMenus($group_id, 0, $menus);
        return $menus;
    }
    /**
     * @param $name
     * @return array
     */
    public function make($name) {
        $group = MenuGroup::whereAlias($name)->firstOrFail();
        return $this->build($group->id);
    }
}