<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-03 17:00
 */
namespace Notadd\Extension\Controllers\Admin;
use Illuminate\Support\Collection;
use Notadd\Admin\Controllers\AbstractAdminController;
use Notadd\Extension\ExtensionManager;
use Symfony\Component\Finder\Finder;
/**
 * Class ExtensionController
 * @package Notadd\Extension\Controllers\Admin
 */
class ExtensionController extends AbstractAdminController {
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $list;
    /**
     * @var \Notadd\Extension\ExtensionManager
     */
    protected $manager;
    /**
     * ExtensionController constructor.
     * @param \Notadd\Extension\ExtensionManager $manager
     */
    public function __construct(ExtensionManager $manager) {
        parent::__construct();
        $this->list = new Collection();
        $this->manager = $manager;
        foreach(Finder::create()->in($this->manager->getExtensionsDir())->directories()->depth(0) as $dir) {
            $directory = $dir->getFilename();
            $this->list->put($directory, $this->setting->get('extension.' . $directory . '.enabled', false));
        }
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->share('list', $this->list);
        return $this->view('admin::extension.index');
    }
    /**
     * @param $directory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($directory) {
        $data = $this->setting->get('extension.' . $directory . '.enabled', false);
        $this->setting->set('extension.' . $directory . '.enabled', !$data);
        return $this->redirect->to('admin/extension');
    }
}