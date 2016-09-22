<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 18:24
 */
namespace Notadd\Install\Controllers;
use Notadd\Foundation\Routing\Abstracts\AbstractController;
use Notadd\Install\Contracts\PrerequisiteContract;
/**
 * Class IndexController
 * @package Notadd\Install\Controllers
 */
class IndexController extends AbstractController {
    /**
     * @var \Notadd\Install\Contracts\PrerequisiteContract
     */
    protected $prerequisite;
    /**
     * IndexController constructor.
     * @param \Notadd\Install\Contracts\PrerequisiteContract $prerequisite
     */
    public function __construct(PrerequisiteContract $prerequisite) {
        parent::__construct();
        $this->prerequisite = $prerequisite;
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function handle() {
        $this->prerequisite->check();
        $errors = $this->prerequisite->getErrors();
        if(count($errors)) {
            $this->share('errors', $errors);
            return $this->view('install::errors');
        } else {
            return $this->view('install::install');
        }
    }
}