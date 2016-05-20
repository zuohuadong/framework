<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-29 00:50
 */
namespace Notadd\Install\Console;
use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Notadd\Foundation\Auth\Models\User;
use Notadd\Install\Requests\InstallRequest;
use PDO;
/**
 * Class InstallCommand
 * @package Notadd\Install\Console
 */
class InstallCommand extends Command {
    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $data;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $filesystem;
    /**
     * @var bool
     */
    protected $isDataSetted = false;
    /**
     * @var string
     */
    protected $name = 'install';
    /**
     * @var string
     */
    protected $description = '应用程序安装器';
    /**
     * @var \Notadd\Setting\Factory
     */
    protected $setting;
    /**
     * InstallCommand constructor.
     * @param \Illuminate\Contracts\Foundation\Application $application
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     */
    public function __construct(Application $application, Filesystem $filesystem) {
        parent::__construct();
        $this->laravel = $application;
        $this->config = $this->laravel->make('config');
        $this->data = Collection::make();
        $this->filesystem = $filesystem;
        $this->setting = $this->laravel->make('setting');
    }
    /**
     * @return void
     */
    protected function createAdministrationUser() {
        $auth = $this->laravel->make('auth');
        $user = User::create([
            'name' => $this->data->get('admin_username'),
            'email' => $this->data->get('admin_email'),
            'password' => bcrypt($this->data->get('admin_password')),
        ]);
        $auth->login($user);
        touch($this->laravel->storagePath() . '/notadd/installed');
    }
    /**
     * @return void
     */
    public function fire() {
        if(!$this->isDataSetted) {
            $this->setDataFromConsoling();
        }
        $this->config->set('database', [
            'fetch' => PDO::FETCH_CLASS,
            'default' => $this->data->get('driver'),
            'connections' => [],
            'migrations' => 'migrations',
            'redis' => [],
        ]);
        switch($this->data->get('driver')) {
            case 'mysql':
                $this->config->set('database.connections.mysql', [
                    'driver' => 'mysql',
                    'host' => $this->data->get('host'),
                    'database' => $this->data->get('database'),
                    'username' => $this->data->get('username'),
                    'password' => $this->data->get('password'),
                    'charset' => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix' => $this->data->get('prefix'),
                    'strict' => false,
                    'engine' => null,
                ]);
                break;
            case 'pgsql':
                $this->config->set('database.connections.pgsql', [
                    'driver' => 'pgsql',
                    'host' => $this->data->get('host'),
                    'database' => $this->data->get('database'),
                    'username' => $this->data->get('username'),
                    'password' => $this->data->get('password'),
                    'charset' => 'utf8',
                    'prefix' => $this->data->get('prefix'),
                    'schema' => 'public',
                ]);
                break;
            case 'sqlite':
                $this->config->set('database.connections.sqlite', [
                    'driver'   => 'sqlite',
                    'database' => database_path('database.sqlite'),
                    'prefix'   => $this->data->get('prefix'),
                ]);
                break;
        }
        $this->call('migrate', [
            '--force' => true,
            '--path' => str_replace($this->laravel->basePath() . DIRECTORY_SEPARATOR, '', realpath(__DIR__ . '/../../../../migrations/')),
        ]);
        $this->setting->set('site.title', $this->data->get('title'));
        $this->setting->set('seo.title', $this->data->get('title'));
        $this->createAdministrationUser();
        $this->writingConfiguration();
        $this->call('vendor:publish', [
            '--force' => true,
            '--tag' => ['theme'],
        ]);
        $this->comment('Application Installed!');
    }
    /**
     * @param \Notadd\Install\Requests\InstallRequest $request
     */
    public function setDataFromCalling(InstallRequest $request) {
        $this->data->put('driver', $request->offsetGet('driver'));
        $this->data->put('host', $request->offsetGet('host'));
        $this->data->put('database', $request->offsetGet('database'));
        $this->data->put('username', $request->offsetGet('username'));
        $this->data->put('password', $request->offsetGet('password'));
        $this->data->put('prefix', $request->offsetGet('prefix'));
        $this->data->put('admin_username', $request->offsetGet('admin_username'));
        $this->data->put('admin_password', $request->offsetGet('admin_password'));
        $this->data->put('admin_password_confirmation', $request->offsetGet('admin_password_confirmation'));
        $this->data->put('admin_email', $request->offsetGet('admin_email'));
        $this->data->put('title', $request->offsetGet('title'));
        $this->isDataSetted = true;
    }
    /**
     * @return void
     */
    public function setDataFromConsoling() {
        $this->data->put('driver', 'mysql');
        $this->data->put('host', $this->ask('数据库服务器：'));
        $this->data->put('database', $this->ask('数据库名：'));
        $this->data->put('username', $this->ask('数据库用户名：'));
        $this->data->put('password', $this->secret('数据库密码：'));
        $this->data->put('prefix', $this->ask('数据库表前缀：'));
        $this->data->put('admin_username', $this->ask('管理员帐号：'));
        $this->data->put('admin_password', $this->secret('管理员密码：'));
        $this->data->put('admin_password_confirmation', $this->secret('重复密码：'));
        $this->data->put('admin_email', $this->ask('电子邮箱：'));
        $this->data->put('title', $this->ask('网站标题：'));
        $this->isDataSetted = true;
    }
    /**
     * @return void
     */
    protected function writingConfiguration() {
        $config = [
            'database' => [
                'fetch' => PDO::FETCH_CLASS,
                'default' => $this->data->get('driver'),
                'connections' => [
                    'mysql' => [
                        'driver' => 'mysql',
                        'host' => $this->data->get('host'),
                        'database' => $this->data->get('database'),
                        'username' => $this->data->get('username'),
                        'password' => $this->data->get('password'),
                        'charset' => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                        'prefix' => $this->data->get('prefix'),
                        'strict' => true,
                    ],
                ],
                'migrations' => 'migrations',
                'redis' => [
                ],
            ]
        ];
        switch($this->data->get('driver')) {
            case 'mysql':
                $config['database']['connections']['mysql'] =  [
                    'driver' => 'mysql',
                    'host' => $this->data->get('host'),
                    'database' => $this->data->get('database'),
                    'username' => $this->data->get('username'),
                    'password' => $this->data->get('password'),
                    'charset' => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix' => $this->data->get('prefix'),
                    'strict' => false,
                    'engine' => null,
                ];
                break;
            case 'pgsql':
                $config['database']['connections']['pgsql'] =  [
                    'driver' => 'pgsql',
                    'host' => $this->data->get('host'),
                    'database' => $this->data->get('database'),
                    'username' => $this->data->get('username'),
                    'password' => $this->data->get('password'),
                    'charset' => 'utf8',
                    'prefix' => $this->data->get('prefix'),
                    'schema' => 'public',
                ];
                break;
            case 'sqlite':
                $config['database']['connections']['sqlite'] =  [
                    'driver'   => 'sqlite',
                    'database' => database_path('database.sqlite'),
                    'prefix'   => $this->data->get('prefix'),
                ];
                break;
        }
        file_put_contents(
            realpath($this->laravel->storagePath() . '/notadd') . DIRECTORY_SEPARATOR . 'config.php',
            '<?php return '.var_export($config, true).';'
        );
    }
}