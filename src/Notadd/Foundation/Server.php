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
use Notadd\Admin\AdminServiceProvider;
use Notadd\Article\ArticleServiceProvider;
use Notadd\Attachment\AttachmentServiceProvider;
use Notadd\Category\CategoryServiceProvider;
use Notadd\Develop\DevelopServiceProvider;
use Notadd\Editor\EditorServiceProvider;
use Notadd\Flash\FlashServiceProvider;
use Notadd\Foundation\Agent\AgentServiceProvider;
use Notadd\Foundation\Auth\Models\User;
use Notadd\Foundation\Console\Kernel as ConsoleKernel;
use Notadd\Extension\ExtensionServiceProvider;
use Notadd\Foundation\Http\HttpServiceProvider;
use Notadd\Foundation\Http\Kernel as HttpKernel;
use Notadd\Foundation\Exceptions\Handler;
use Notadd\Foundation\Image\ImageServiceProvider;
use Notadd\Install\InstallServiceProvider;
use Notadd\Link\LinkServiceProvider;
use Notadd\Menu\MenuServiceProvider;
use Notadd\Page\PageServiceProvider;
use Notadd\Payment\PaymentServiceProvider;
use Notadd\Sitemap\SitemapServiceProvider;
use Notadd\Theme\ThemeServiceProvider;
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
            $this->application->register(AgentServiceProvider::class);
            $this->application->register(ImageServiceProvider::class);
            $this->application->register(ThemeServiceProvider::class);
            $this->application->register(MenuServiceProvider::class);
            $this->application->register(EditorServiceProvider::class);
            $this->application->register(FlashServiceProvider::class);
            $this->application->register(CategoryServiceProvider::class);
            $this->application->register(ArticleServiceProvider::class);
            $this->application->register(AttachmentServiceProvider::class);
            $this->application->register(SitemapServiceProvider::class);
            $this->application->register(HttpServiceProvider::class);
            $this->application->register(LinkServiceProvider::class);
            $this->application->register(PageServiceProvider::class);
            $this->application->register(PaymentServiceProvider::class);
            $this->application->register(AdminServiceProvider::class);
            $this->application->register(DevelopServiceProvider::class);
            $this->application->register(ExtensionServiceProvider::class);
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