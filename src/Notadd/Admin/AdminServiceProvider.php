<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-29 18:19
 */
namespace Notadd\Admin;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Routing\Events\RouteMatched;
use Notadd\Admin\Controllers\AdminController;
use Notadd\Admin\Controllers\AuthController;
use Notadd\Admin\Controllers\PasswordController;
use Notadd\Admin\Events\GetAdminMenu;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
/**
 * Class AdminServiceProvider
 * @package Notadd\Admin
 */
class AdminServiceProvider extends AbstractServiceProvider {
    /**
     * @param \Illuminate\Contracts\Auth\Access\Gate $gate
     */
    public function boot(Gate $gate) {
        $this->initAdminConfig();
        $this->loadViewsFrom(realpath($this->app->basePath() . '/../template/admin/views'), 'admin');
        $this->router->group(['prefix' => 'admin'], function () {
            $this->router->get('login', AuthController::class . '@getLogin');
            $this->router->post('login', AuthController::class . '@postLogin');
            $this->router->get('logout', AuthController::class . '@getLogout');
            $this->router->get('register', AuthController::class . '@getRegister');
            $this->router->post('register', AuthController::class . '@postRegister');
        });
        $this->router->group(['middleware' => 'auth.admin', 'prefix' => 'admin'], function () {
            $this->router->get('/', AdminController::class . '@init');
            $this->router->resource('password', PasswordController::class);
        });
        $this->events->listen(RouteMatched::class, function () {
            $this->events->fire(new GetAdminMenu($this->app, $this->config));
            if($this->request->is('admin*')) {
                $menu = $this->config->get('admin');
                foreach($menu as $top_key => $top) {
                    if(isset($top['sub'])) {
                        foreach($top['sub'] as $one_key => $one) {
                            if(isset($one['sub'])) {
                                $active = false;
                                foreach((array)$one['active'] as $rule) {
                                    if($this->request->is($rule)) {
                                        $active = true;
                                    }
                                }
                                if($active) {
                                    $menu[$top_key]['sub'][$one_key]['active'] = 'open';
                                } else {
                                    $menu[$top_key]['sub'][$one_key]['active'] = '';
                                }
                                foreach($one['sub'] as $two_key=>$two) {
                                    $active = false;
                                    foreach((array)$two['active'] as $rule) {
                                        if($this->request->is($rule)) {
                                            $active = true;
                                        }
                                    }
                                    if($active) {
                                        $menu[$top_key]['sub'][$one_key]['sub'][$two_key]['active'] = 'open';
                                    } else {
                                        $menu[$top_key]['sub'][$one_key]['sub'][$two_key]['active'] = '';
                                    }
                                }
                            } else {
                                if($this->request->is($one['active'])) {
                                    $menu[$top_key]['sub'][$one_key]['active'] = 'active';
                                } else {
                                    $menu[$top_key]['sub'][$one_key]['active'] = '';
                                }
                            }
                        }
                    }
                }
                $this->config->set('admin', $menu);
            }
        });
    }
    /**
     * @return void
     */
    public function initAdminConfig() {
        $this->config->set('admin', [
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
                            'admin/attachment*',
                            'admin/sitemap*',
                            'admin/theme*',
                            'admin/flash*',
                            'admin/ad*',
                            'admin/menu*',
                            'admin/third*',
                            'admin/payment*',
                            'admin/link*',
                        ],
                        'icon'  => 'fa-table',
                        'sub' => [
                            [
                                'title' => '主题管理',
                                'active' => 'admin/theme*',
                                'url' => 'admin/theme',
                            ],
                            [
                                'title' => '附件管理',
                                'active' => 'admin/attachment*',
                                'url' => 'admin/attachment',
                            ],
                            [
                                'title' => 'Sitemap组件',
                                'active' => 'admin/sitemap*',
                                'url' => 'admin/sitemap',
                            ],
                            [
                                'title' => '菜单组件',
                                'active' => 'admin/menu*',
                                'url'   => 'admin/menu',
                            ],
                            [
                                'title' => '幻灯片组件',
                                'active' => 'admin/flash*',
                                'url' => 'admin/flash',
                            ],
                            [
                                'title' => '社交组件',
                                'active' => 'admin/third*',
                                'url' => 'admin/third',
                            ],
                            [
                                'title' => '支付组件',
                                'active' => 'admin/payment*',
                                'url' => 'admin/payment',
                            ],
                            [
                                'title' => '友情链接组件',
                                'active' => 'admin/link*',
                                'url' => 'admin/link',
                            ],
                            [
                                'title' => '搜索组件',
                                'active' => 'admin/search*',
                                'url' => 'admin/search',
                            ]
                        ]
                    ],
                    'develop' => [
                        'title' => '开发者工具',
                        'active' => [
                            'admin/migration*'
                        ],
                        'icon'  => 'fa-shekel',
                        'sub' => [
                            [
                                'title' => 'Migration工具',
                                'active' => 'admin/migration*',
                                'url' => 'admin/migration'
                            ],
                        ]
                    ],
                    'extension' => [
                        'title' => '插件配置',
                        'active' => [
                            'admin/extension*'
                        ],
                        'icon' => 'fa-shekel',
                        'sub' => [
                            [
                                'title' => '插件管理',
                                'active' => 'admin/extension*',
                                'url' => 'admin/extension'
                            ]
                        ]
                    ]
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