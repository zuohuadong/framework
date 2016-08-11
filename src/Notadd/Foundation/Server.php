<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-18 16:28
 */
namespace Notadd\Foundation;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Contracts\Console\Kernel as ConsoleKernelContract;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Http\Kernel as HttpKernelContract;
use Illuminate\Support\Arr;
use Notadd\Foundation\Agent\AgentServiceProvider;
use Notadd\Foundation\Auth\Models\User;
use Notadd\Foundation\Console\Kernel as ConsoleKernel;
use Notadd\Foundation\Http\AppServiceProvider;
use Notadd\Foundation\Http\Kernel as HttpKernel;
use Notadd\Foundation\Exceptions\Handler;
use Notadd\Install\InstallServiceProvider;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
/**
 * Class Server
 * @package Notadd\Foundation
 */
class Server {
    /**
     * @var \Notadd\Foundation\Application
     */
    private $application;
    /**
     * @var string
     */
    private $path;
    /**
     * Server constructor.
     * @param $path
     */
    public function __construct($path) {
        define('NOTADD_START', microtime(true));
        $this->path = realpath($path);
        $this->application = new Application($this->path);
    }
    /**
     * @return $this
     */
    public function init() {
        $config = Arr::collapse([
            $this->loadIlluminateConfiguration(),
            $this->loadFiledConfiguration()
        ]);
        $this->application->instance('env', 'production');
        $this->application->instance('config', new Repository($config));
        $this->application->registerConfiguredProviders();
        $this->application->singleton(HttpKernelContract::class, HttpKernel::class);
        $this->application->singleton(ConsoleKernelContract::class, ConsoleKernel::class);
        $this->application->singleton(ExceptionHandler::class, Handler::class);
        if($this->application->isInstalled()) {
            $this->application->register(AppServiceProvider::class);
        } else {
            $this->application->register(InstallServiceProvider::class);
        }
        return $this;
    }
    /**
     * @return array|mixed
     */
    protected function loadFiledConfiguration() {
        $file = realpath($this->application->storagePath() . '/notadd') . DIRECTORY_SEPARATOR . 'config.php';
        if(file_exists($file)) {
            return require $file;
        } else {
            return [];
        }
    }
    /**
     * @return array
     */
    protected function loadIlluminateConfiguration() {
        return [
            'admin' => [
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
                                'admin/smtp*',
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
                                    'title' => 'SMTP配置',
                                    'active' => 'admin/smtp*',
                                    'url' => 'admin/smtp',
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
                                'admin/search*',
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
            ],
            'app' => [
                'debug' => true,
                'url' => 'http://localhost',
                'timezone' => 'UTC+8',
                'locale' => 'en',
                'fallback_locale' => 'en',
                'key' => 'GERojpSdTnQQbr77s5iXIa1c7Ne7NO4d',
                'cipher' => 'AES-256-CBC',
                'log' => 'daily'
            ],
            'auth' => [
                'defaults' => [
                    'guard' => 'web',
                    'passwords' => 'users',
                ],
                'guards' => [
                    'web' => [
                        'driver' => 'session',
                        'provider' => 'users',
                    ],
                    'api' => [
                        'driver' => 'token',
                        'provider' => 'users',
                    ],
                ],
                'providers' => [
                    'users' => [
                        'driver' => 'eloquent',
                        'model' => User::class,
                    ],
                ],
                'passwords' => [
                    'users' => [
                        'provider' => 'users',
                        'email' => 'auth.emails.password',
                        'table' => 'password_resets',
                        'expire' => 60,
                    ],
                ],
            ],
            'cache' => [
                'default' => 'file',
                'stores' => [
                    'file' => [
                        'driver' => 'file',
                        'path' => $this->application->storagePath() . '/cache',
                    ],
                ],
                'prefix' => 'notadd',
            ],
            'database' => [
                'migrations' => 'migrations',
            ],
            'filesystems' => [
                'default' => 'local',
                'cloud' => 's3',
                'disks' => []
            ],
            'image' => [
                'driver' => 'gd'
            ],
            'mail' => [
                'driver' => 'mail',
            ],
            'session' => [
                'driver' => 'file',
                'lifetime' => 120,
                'expire_on_close' => false,
                'encrypt' => false,
                'files' => $this->application->storagePath() . '/sessions',
                'connection' => null,
                'table' => 'sessions',
                'lottery' => [2, 100],
                'cookie' => 'notadd_session',
                'path' => '/',
                'domain' => null,
                'secure' => false,
            ],
            'sitemap' => [
                'use_cache' => false,
                'cache_key' => 'notadd_sitemap',
                'cache_duration' => 3600,
                'escaping' => true,
            ],
            'view' => [
                'paths' => [],
                'compiled' => $this->application->storagePath() . '/views',
            ]
        ];
    }
    /**
     * @param $path
     * @return $this
     */
    public function usePublicPath($path) {
        $this->application->usePublicPath($path);
        return $this;
    }
    /**
     * @return void
     */
    public function terminate() {
        $kernel = $this->application->make(HttpKernelContract::class);
        $response = $kernel->handle($request = Request::capture());
        $response->send();
        $kernel->terminate($request, $response);
    }
    /**
     * @return void
     */
    public function console() {
        $this->application->singleton(ConsoleKernelContract::class, ConsoleKernel::class);
        $this->application->singleton(ExceptionHandler::class, Handler::class);
        $kernel = $this->application->make(ConsoleKernelContract::class);
        $status = $kernel->handle($input = new ArgvInput, new ConsoleOutput);
        $kernel->terminate($input, $status);
        exit($status);
    }
}