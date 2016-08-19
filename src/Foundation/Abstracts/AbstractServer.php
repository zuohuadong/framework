<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-19 22:47
 */
namespace Notadd\Foundation\Abstracts;
use Illuminate\Config\Repository as ConfigRepository;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Notadd\Foundation\Application;
/**
 * Class AbstractServer
 * @package Notadd\Foundation\Abstracts
 */
abstract class AbstractServer {
    /**
     * @var string
     */
    protected $path;
    /**
     * AbstractServer constructor.
     * @param $path
     */
    public function __construct($path) {
        $this->path = $path;
    }
    /**
     * @return \Notadd\Foundation\Application
     */
    protected function getApp() {
        date_default_timezone_set('UTC');
        $app = new Application($this->path);
        $app->instance('env', 'production');
        $app->instance('config', $config = $this->getIlluminateConfig($app));
        $this->registerLogger($app);
        $app->register('Illuminate\Bus\BusServiceProvider');
        $app->register('Illuminate\Cache\CacheServiceProvider');
        $app->register('Illuminate\Filesystem\FilesystemServiceProvider');
        $app->register('Illuminate\Hashing\HashServiceProvider');
        $app->register('Illuminate\Mail\MailServiceProvider');
        $app->register('Illuminate\View\ViewServiceProvider');
        $app->register('Illuminate\Validation\ValidationServiceProvider');
        $app->boot();
        return $app;
    }
    /**
     * @return string
     */
    public function getPath() {
        return $this->path;
    }
    /**
     * @param \Notadd\Foundation\Application $app
     * @return \Illuminate\Config\Repository
     */
    protected function getIlluminateConfig(Application $app) {
        return new ConfigRepository([
            'view' => [
                'paths' => [],
                'compiled' => $app->storagePath() . '/views',
            ],
            'mail' => [
                'driver' => 'mail',
            ],
            'cache' => [
                'default' => 'file',
                'stores' => [
                    'file' => [
                        'driver' => 'file',
                        'path' => $app->storagePath() . '/cache',
                    ],
                ],
                'prefix' => 'flarum',
            ],
            'filesystems' => [
                'default' => 'local',
                'cloud' => 's3',
                'disks' => [
                    'flarum-avatars' => [
                        'driver' => 'local',
                        'root' => $app->publicPath() . '/assets/avatars'
                    ]
                ]
            ]
        ]);
    }
    /**
     * @param Application $app
     */
    protected function registerLogger(Application $app) {
        $logger = new Logger($app->environment());
        $logPath = $app->storagePath() . '/logs/notadd.log';
        $handler = new StreamHandler($logPath, Logger::DEBUG);
        $handler->setFormatter(new LineFormatter(null, null, true, true));
        $logger->pushHandler($handler);
        $app->instance('log', $logger);
        $app->alias('log', 'Psr\Log\LoggerInterface');
    }
    /**
     * @param string $path
     */
    public function setPath($path) {
        $this->path = $path;
    }
}