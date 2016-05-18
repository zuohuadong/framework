<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-12 15:04
 */
namespace Notadd\Attachment\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Notadd\Admin\Controllers\AbstractAdminController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Class UploadController
 * @package Notadd\Attachment\Controllers\Admin\Configurations
 */
class ConfigurationController extends AbstractAdminController {
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $file;
    /**
     * ConfigurationController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->file = $this->app->make('files');
    }
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index() {
        $this->share('engine', $this->setting->get('attachment.engine'));
        $this->share('size_file_limit', $this->setting->get('attachment.size.file.limit'));
        $this->share('size_image_limit', $this->setting->get('attachment.size.image.limit'));
        $this->share('size_video_limit', $this->setting->get('attachment.size.video.limit'));
        $this->share('allow_image_format', $this->setting->get('attachment.format.allow.image'));
        $this->share('allow_catcher_format', $this->setting->get('attachment.format.allow.catcher'));
        $this->share('allow_video_format', $this->setting->get('attachment.format.allow.video'));
        $this->share('allow_file_format', $this->setting->get('attachment.format.allow.file'));
        $this->share('allow_manager_image_format', $this->setting->get('attachment.format.allow.manager.image'));
        $this->share('allow_manager_file_format', $this->setting->get('attachment.format.allow.manager.file'));
        $this->share('allow_watermark', $this->setting->get('attachment.watermark'));
        $this->share('watermark_file', $this->setting->get('attachment.watermark.file'));
        return $this->view('attachment.configuration');
    }
    /**
     * @param $path
     * @param $dots
     * @param null $data
     * @return \Illuminate\Support\Collection|null
     */
    protected function pathSplit($path, $dots, $data = null) {
        $dots = explode(',', $dots);
        $data = $data ? $data : new Collection();
        $offset = 0;
        foreach($dots as $dot) {
            $data->push(Str::substr($path, $offset, $dot));
            $offset += $dot;
        }
        return $data;
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) {
        $this->setting->set('attachment.engine', $request->get('engine'));
        $this->setting->set('attachment.size.file.limit', $request->get('size_file_limit'));
        $this->setting->set('attachment.size.image.limit', $request->get('size_image_limit'));
        $this->setting->set('attachment.size.video.limit', $request->get('size_video_limit'));
        $this->setting->set('attachment.format.allow.image', $request->get('allow_image_format'));
        $this->setting->set('attachment.format.allow.catcher', $request->get('allow_catcher_format'));
        $this->setting->set('attachment.format.allow.video', $request->get('allow_video_format'));
        $this->setting->set('attachment.format.allow.file', $request->get('allow_file_format'));
        $this->setting->set('attachment.format.allow.manager.image', $request->get('allow_manager_image_format'));
        $this->setting->set('attachment.format.allow.manager.file', $request->get('allow_manager_file_format'));
        $this->setting->set('attachment.watermark', $request->get('allow_watermark'));
        if($watermark = $request->file('watermark_file')) {
            if($watermark instanceof UploadedFile) {
                $hash = hash_file('md5', $watermark->getPathname(), false);
                $dictionary = $this->pathSplit($hash, '12', Collection::make([
                    'uploads'
                ]))->implode(DIRECTORY_SEPARATOR);
                if(!$this->file->isDirectory(app_path($dictionary))) {
                    $this->file->makeDirectory(app_path($dictionary), 0777, true, true);
                }
                $file = Str::substr($hash, 12, 20) . '.' . $watermark->getClientOriginalExtension();
                if(!$this->file->exists($dictionary . DIRECTORY_SEPARATOR . $file)) {
                    $watermark->move($dictionary, $file);
                }
                $this->setting->set('attachment.watermark.file', $this->pathSplit($hash, '12,20', Collection::make([
                        'uploads'
                    ]))->implode('/') . '.' . $watermark->getClientOriginalExtension());
            }
        }
        return $this->redirect->back();
    }
}