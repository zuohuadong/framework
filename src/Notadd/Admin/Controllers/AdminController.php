<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 10:58
 */
namespace Notadd\Admin\Controllers;
use Notadd\Foundation\Database\DatabaseManager;
use PDO;
/**
 * Class AdminController
 * @package Notadd\Admin\Controllers
 */
class AdminController extends AbstractAdminController {
    /**
     * @param \Notadd\Foundation\Database\DatabaseManager $manager
     * @return \Illuminate\Contracts\View\View
     */
    public function init(DatabaseManager $manager) {
        $connection = $manager->connection($manager->getDefaultConnection());
        $this->share('article_count', 0);
        $this->share('mysql_version', $connection->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION));
        $this->share('post_max_size', $this->show('post_max_size'));
        $this->share('upload_max_filesize', $this->show('upload_max_filesize'));
        return $this->view('index');
    }
    /**
     * @param $value
     * @return string
     */
    protected function show($value) {
        switch($result = get_cfg_var($value)) {
            case 0:
                return '<font color="red">×</font>';
            case 1:
                return '<font color="green">√</font>';
            default:
                return $result;
        }
    }
}