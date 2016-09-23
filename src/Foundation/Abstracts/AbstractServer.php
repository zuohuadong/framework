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
use Illuminate\Encryption\Encrypter;
use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\Mail\MailServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationServiceProvider;
use Illuminate\View\ViewServiceProvider;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Notadd\Admin\AdminServiceProvider;
use Notadd\Api\ApiServiceProvider;
use Notadd\Extension\ExtensionServiceProvider;
use Notadd\Foundation\Application;
use Notadd\Foundation\Auth\AuthServiceProvider;
use Notadd\Foundation\Database\DatabaseServiceProvider;
use Notadd\Foundation\Http\HttpServiceProvider;
use Notadd\Foundation\Passport\PassportServiceProvider;
use Notadd\Foundation\Routing\RouterServiceProvider;
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
        $app->instance('config', $config = $this->getIlluminateConfig($app));
        $app->instance('encrypter', $this->getEncrypter());
        $app->instance('env', 'production');
        $app->instance('hash', $this->getHashing());
        $this->registerLogger($app);
        $app->register(AuthServiceProvider::class);
        $app->register(BusServiceProvider::class);
        $app->register(CacheServiceProvider::class);
        $app->register(DatabaseServiceProvider::class);
        $app->register(FilesystemServiceProvider::class);
        $app->register(HashServiceProvider::class);
        $app->register(MailServiceProvider::class);
        $app->register(PassportServiceProvider::class);
        $app->register(RouterServiceProvider::class);
        $app->register(ValidationServiceProvider::class);
        $app->register(ViewServiceProvider::class);
        $app->register(SettingServiceProvider::class);
        if($app->isInstalled()) {
            $setting = $app->make(SettingsRepository::class);
            $config->set('mail.driver', $setting->get('mail.driver', 'smtp'));
            $config->set('mail.host', $setting->get('mail.host'));
            $config->set('mail.port', $setting->get('mail.port'));
            $config->set('mail.from.address', $setting->get('mail.from'));
            $config->set('mail.from.name', $setting->get('site.title', 'Notadd'));
            $config->set('mail.encryption', $setting->get('mail.encryption'));
            $config->set('mail.username', $setting->get('mail.username'));
            $config->set('mail.password', $setting->get('mail.password'));
            $app->register(HttpServiceProvider::class);
            $app->register(ApiServiceProvider::class);
            $app->register(AdminServiceProvider::class);
            $app->register(ExtensionServiceProvider::class);
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
                        'database' => 'notadd.io',
                        'username' => 'root',
                        'password' => '123456789',
                        'charset' => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                        'prefix' => 'pre_',
                        'strict' => true,
                        'engine' => null,
                    ],
                ],
                'migrations' => 'migrations'
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
    }
    /**
     * @param string $path
     */
    public function setPath($path) {
        $this->path = $path;
    }
    /**
     * @return \Illuminate\Encryption\Encrypter
     */
    protected function getEncrypter() {
        $cipher = 'AES-256-CBC';
        $key = 'base64:BlPAX+TJIJqw85JAFiTFOhw6sj9lLiR+l8Qvf6PHlAY=';
        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }
        return new Encrypter($key, $cipher);
    }
    /**
     * @return \Illuminate\Hashing\BcryptHasher
     */
    protected function getHashing() {
        return new BcryptHasher;
    }
}