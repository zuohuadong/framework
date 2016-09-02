<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-19 22:47
 */
namespace Notadd\Foundation\Abstracts;
use Illuminate\Bus\BusServiceProvider;
use Illuminate\Cache\CacheServiceProvider;
use Illuminate\Config\Repository as ConfigRepository;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Validation\ValidationServiceProvider;
use Illuminate\View\ViewServiceProvider;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Notadd\Foundation\Application;
use Notadd\Foundation\Database\DatabaseServiceProvider;
use Notadd\Foundation\Http\HttpServiceProvider;
use Notadd\Setting\Contracts\SettingsRepository;
use Notadd\Setting\SettingServiceProvider;
use PDO;
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
        $app->register(BusServiceProvider::class);
        $app->register(CacheServiceProvider::class);
        $app->register(DatabaseServiceProvider::class);
        $app->register(FilesystemServiceProvider::class);
        $app->register(HashServiceProvider::class);
        $app->register(MailServiceProvider::class);
        $app->register(ValidationServiceProvider::class);
        $app->register(ViewServiceProvider::class);
        $app->register(SettingServiceProvider::class);
        if($app->isInstalled()) {
            //$setting = $app->make(SettingsRepository::class);
            //$config->set('mail.driver', $settings->get('mail.driver'));
            //$config->set('mail.host', $settings->get('mail.host'));
            //$config->set('mail.port', $settings->get('mail.port'));
            //$config->set('mail.from.address', $settings->get('mail.from'));
            //$config->set('mail.from.name', $settings->get('site.title', 'Notadd'));
            //$config->set('mail.encryption', $settings->get('mail.encryption'));
            //$config->set('mail.username', $settings->get('mail.username'));
            //$config->set('mail.password', $settings->get('mail.password'));
            $app->register(HttpServiceProvider::class);
        }
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
            'cache' => [
                'default' => 'file',
                'stores' => [
                    'file' => [
                        'driver' => 'file',
                        'path' => $app->storagePath() . '/cache',
                    ],
                ],
                'prefix' => 'notadd',
            ],
            'database' => [
                'fetch' => PDO::FETCH_OBJ,
                'default' => 'mysql',
                'connections' => [
                    'mysql' => [
                        'driver' => 'mysql',
                        'host' => 'localhost',
                        'port' => '3306',
                        'database' => '',
                        'username' => '',
                        'password' => '',
                        'charset' => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                        'prefix' => 'pre_',
                        'strict' => true,
                        'engine' => null,
                    ],
                ]
            ],
            'filesystems' => [
                'default' => 'local',
            ],
            'mail' => [
                'driver' => 'mail',
            ]
        ]);
    }
    /**
     * @param Application $app
     */
    protected function registerLogger(Application $app) {
        $logger = new Logger($app->environment());
        $logPath = $app->storagePath() . '/logs/notadd.log';
        $handler = new RotatingFileHandler($logPath, 0, Logger::DEBUG);
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