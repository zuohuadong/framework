<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-27 23:17
 */
namespace Notadd\Install\Controllers;
use Notadd\Foundation\Routing\Controller;
use Notadd\Install\Requests\InstallRequest;
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
     */
    public function __construct() {
        parent::__construct();
        $this->command = $this->getCommand('install');
    }
    /**
     * @param \Notadd\Install\Requests\InstallRequest $request
     */
    public function handle(InstallRequest $request) {
        $input = new ArrayInput(['command' => 'install']);
        $output = new BufferedOutput();
        $this->command->setDataFromCalling($request);
        $this->command->run($input, $output);
        echo $output->fetch();
    }
}