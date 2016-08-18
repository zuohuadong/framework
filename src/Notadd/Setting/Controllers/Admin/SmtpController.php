<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-08 17:54
 */
namespace Notadd\Setting\Controllers\Admin;
use Notadd\Foundation\Abstracts\AbstractAdminController;
/**
 * Class SmtpController
 * @package Notadd\Setting\Controllers\Admin
 */
class SmtpController extends AbstractAdminController {
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->share('', $this->setting->get(''));
        return $this->view('admin::config.smtp');
    }
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store() {
        $this->setting->set('', $this->request->offsetGet(''));
        return $this->redirect->to('admin/smtp')->with('message', '更新SMTP信息成功！');
    }
}