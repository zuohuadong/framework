<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-01 10:20
 */
namespace Notadd\Theme\Controllers\Admin;
use Notadd\Foundation\Abstracts\AbstractAdminController;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
/**
 * Class PublishController
 * @package Notadd\Theme\Controllers\Admin
 */
class PublishController extends AbstractAdminController {
    /**
     * @param $alias
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($alias) {
        $command = $this->getCommand('vendor:publish');
        $input = new ArrayInput([
            '--tag' => [$alias],
            '--force' => true
        ]);
        $output = new BufferedOutput();
        $command->run($input, $output);
        return $this->redirect->to('admin/theme')->with('message', $output->fetch());
    }
}