<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-11-20 18:44
 */
namespace Notadd\Admin\Controllers;
use Notadd\Foundation\Auth\ResetsPasswords;
/**
 * Class PasswordController
 * @package Notadd\Admin\Controllers
 */
class PasswordController extends AbstractAdminController {
    use ResetsPasswords;
    /**
     * PasswordController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->middleware('guest.admin');
    }
}