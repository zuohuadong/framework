<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-29 18:19
 */
namespace Notadd\Admin;
use Illuminate\Support\ServiceProvider;
use Notadd\Admin\Events\GetAdminMenu;
use Notadd\Foundation\Traits\InjectConfigTrait;
use Notadd\Foundation\Traits\InjectEventsTrait;
use Notadd\Foundation\Traits\InjectRequestTrait;
use Notadd\Foundation\Traits\InjectRouterTrait;
/**
 * Class AdminServiceProvider
 * @package Notadd\Admin
 */
class AdminServiceProvider extends ServiceProvider {
    use InjectConfigTrait, InjectEventsTrait, InjectRequestTrait, InjectRouterTrait;
    /**
     * @return void
     */
    public function boot() {
        $this->initAdminConfig();
        $this->loadViewsFrom(realpath($this->app->basePath() . '/../template/admin/views'), 'admin');
        $this->getRouter()->group(['namespace' => 'Notadd\Admin\Controllers'], function () {
            $this->getRouter()->group(['prefix' => 'admin'], function () {
                $this->getRouter()->get('login', 'AuthController@getLogin');
                $this->getRouter()->post('login', 'AuthController@postLogin');
                $this->getRouter()->get('logout', 'AuthController@getLogout');
                $this->getRouter()->get('register', 'AuthController@getRegister');
                $this->getRouter()->post('register', 'AuthController@postRegister');
                $this->getRouter()->controllers(['password' => 'PasswordController']);
            });
            $this->getRouter()->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
                $this->getRouter()->get('/', 'AdminController@init');
                $this->getRouter()->get('password', 'AuthController@getPassword');
            });
        });
        $this->getEvents()->listen('router.matched', function () {
            $this->getEvents()->fire(new GetAdminMenu($this->app, $this->getConfig()));
            if($this->getRequest()->is('admin*')) {
                $menu = $this->getConfig()->get('admin');
                foreach($menu as $top_key => $top) {
                    if(isset($top['sub'])) {
                        foreach($top['sub'] as $one_key => $one) {
                            if(isset($one['sub'])) {
                                $active = false;
                                foreach((array)$one['active'] as $rule) {
                                    if($this->getRequest()->is($rule)) {
                                        $active = true;
                                    }
                                }
                                if($active) {
                                    $menu[$top_key]['sub'][$one_key]['active'] = 'open';
                                } else {
                                    $menu[$top_key]['sub'][$one_key]['active'] = '';
                                }
                            } else {
                                if($this->getRequest()->is($one['active'])) {
                                    $menu[$top_key]['sub'][$one_key]['active'] = 'active';
                                } else {
                                    $menu[$top_key]['sub'][$one_key]['active'] = '';
                                }
                            }
                        }
                    }
                }
                $this->getConfig()->set('admin', $menu);
            }
        });
    }
    /**
     * @return void
     */
    public function initAdminConfig() {
        $this->getConfig()->set('admin', [
            'general' => [
                'title' => '概略导航',
                'active' => 'admin',
                'sub' => [
                    [
                        'title' => '仪表盘',
                        'active' => 'admin',
                        'url'   => 'admin',
                        'icon'  => 'fa-dashboard',
                    ]
                ]
            ],
            'group' => [
                'title' => '组件导航',
                'active' => '',
                'sub' => [
                    'config' => [
                        'title' => '网站管理',
                        'active' => [
                            'admin/cache*',
                            'admin/seo*',
                            'admin/site*',
                            'admin/third*',
                        ],
                        'icon'  => 'fa-cogs',
                        'sub' => [
                            [
                                'title' => '网站信息',
                                'active' => 'admin/site*',
                                'url' => 'admin/site',
                            ],
                            [
                                'title' => 'SEO设置',
                                'active' => 'admin/seo*',
                                'url' => 'admin/seo',
                            ],
                            [
                                'title' => '缓存管理',
                                'active' => 'admin/cache*',
                                'url' => 'admin/cache',
                            ],
                            [
                                'title' => '第三方登录',
                                'active' => 'admin/third*',
                                'url' => 'admin/third',
                            ]
                        ]
                    ],
                    'content' => [
                        'title' => '内容管理',
                        'active' => [
                            'admin/category*',
                            'admin/article*',
                            'admin/page*',
                            'admin/recycle*',
                        ],
                        'icon'  => 'fa-building',
                        'sub' => [
                            [
                                'title' => '分类管理',
                                'active' => 'admin/category*',
                                'url' => 'admin/category',
                            ],
                            [
                                'title' => '文章管理',
                                'active' => 'admin/article*',
                                'url' => 'admin/article',
                            ],
                            [
                                'title' => '页面管理',
                                'active' => 'admin/page*',
                                'url' => 'admin/page',
                            ],
                        ]
                    ],
                    'group' => [
                        'title' => '组件管理',
                        'active' => [
                            'admin/theme*',
                            'admin/flash*',
                            'admin/ad*',
                            'admin/menu*',
                        ],
                        'icon'  => 'fa-table',
                        'sub' => [
                            [
                                'title' => '主题管理',
                                'active' => 'admin/theme*',
                                'url' => 'admin/theme',
                            ],
                            [
                                'title' => '菜单管理',
                                'active' => 'admin/menu*',
                                'url'   => 'admin/menu',
                                'icon'  => 'fa-paper-plane',
                            ]
                        ]
                    ],
                ]
            ],
        ]);
    }
    /**
     * @return void
     */
    public function register() {
    }
}