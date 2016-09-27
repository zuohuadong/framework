<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 18:36
 */
namespace Notadd\Install\Controllers;
use Notadd\Foundation\Routing\Abstracts\AbstractController;
use Notadd\Foundation\Routing\Responses\RedirectResponse;
use Notadd\Install\Contracts\Prerequisite;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\StreamOutput;
/**
 * Class InstallController
 * @package Notadd\Install\Controllers
 */
class InstallController extends AbstractController {
    /**
     * @var \Notadd\Install\Commands\InstallCommand
     */
    protected $command;
    /**
     * @var \Notadd\Install\Contracts\Prerequisite
     */
    protected $prerequisite;
    /**
     * InstallController constructor.
     * @param \Notadd\Install\Contracts\Prerequisite $prerequisite
     */
    public function __construct(Prerequisite $prerequisite) {
        parent::__construct();
        $this->prerequisite = $prerequisite;
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->prerequisite->check();
        $errors = $this->prerequisite->getErrors();
        if(count($errors)) {
            $this->share('errors', $errors);
            return $this->view('install::errors');
        } else {
            return $this->view('install::install');
        }
    }
    /**
     * @return \Notadd\Foundation\Routing\Responses\RedirectResponse
     */
    public function store() {
        $this->command = $this->getCommand('install');
        $data = $this->request->getParsedBody();
        $this->command->setDataFromController($data);
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        $this->command->run($input, $output);
        return new RedirectResponse(url('/'));
    }
}