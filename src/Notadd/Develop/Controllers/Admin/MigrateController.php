<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Develop\Controllers\Admin;
use Notadd\Foundation\Abstracts\AbstractAdminController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
/**
 * Class MigrateController
 * @package Notadd\Develop\Controllers\Admin
 */
class MigrateController extends AbstractAdminController {
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store() {
        $command = $this->getCommand('migrate');
        $input = new ArrayInput([]);
        $output = new BufferedOutput();
        $command->run($input, $output);
        $this->share('message', $output->fetch());
        return $this->redirect->to('admin/migration')->with('message', $output->fetch());
    }
}