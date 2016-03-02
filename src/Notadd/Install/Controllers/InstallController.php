<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-27 23:17
 */
namespace Notadd\Install\Controllers;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Logging\Log;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Events\Dispatcher;
use Illuminate\Routing\Redirector;
use Notadd\Foundation\Routing\Controller;
use Notadd\Foundation\SearchEngine\Optimization;
use Notadd\Install\Requests\InstallRequest;
use Notadd\Setting\Factory as SettingFactory;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
/**
 * Class InstallController
 * @package Notadd\Install\Controllers
 */
class InstallController extends Controller {
    /**
     * @var \Notadd\Install\Console\InstallCommand
     */
    protected $command;
    /**
     * InstallController constructor.
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Events\Dispatcher $events
     * @param \Illuminate\Contracts\Logging\Log $log
     * @param \Illuminate\Routing\Redirector $redirect
     * @param \Notadd\Setting\Factory $setting
     * @param \Notadd\Foundation\SearchEngine\Optimization $seo
     * @param \Illuminate\Contracts\View\Factory $view
     */
    public function __construct(Application $app, Dispatcher $events, Log $log, Redirector $redirect, SettingFactory $setting, Optimization $seo, ViewFactory $view) {
        parent::__construct($app, $events, $log, $redirect, $setting, $seo, $view);
        $this->command = $this->getCommand('install');
    }
    /**
     * @param \Notadd\Install\Requests\InstallRequest $request
     */
    public function handle(InstallRequest $request) {
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        $this->command->setDataFromCalling($request);
        $this->command->run($input, $output);
        echo $output->fetch();
    }
}