<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 18:36
 */
namespace Notadd\Install\Controllers;
use Zend\Diactoros\Response;
/**
 * Class InstallController
 * @package Notadd\Install\Controllers
 */
class InstallController {
    /**
     * @return \Zend\Diactoros\Response
     */
    public function handle() {
        return new Response($body);
    }
}