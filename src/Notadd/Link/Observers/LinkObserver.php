<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 14:24
 */
namespace Notadd\Link\Observers;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Notadd\Link\Models\Link;
use Symfony\Component\HttpFoundation\File\UploadedFile;
/**
 * Class LinkObserver
 * @package Notadd\Link\Observers
 */
class LinkObserver {
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $file;
    /**
     * ArticleObserver constructor.
     * @param \Illuminate\Filesystem\Filesystem $file
     */
    public function __construct(Filesystem $file) {
        $this->file = $file;
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
     * @param \Notadd\Link\Models\Link $link
     */
    public function updating(Link $link) {
        $thumbImage = $link->getAttribute('icon');
        if($thumbImage) {
            if($thumbImage instanceof UploadedFile) {
                $hash = hash_file('md5', $thumbImage->getPathname(), false);
                $dictionary = $this->pathSplit($hash, '12', Collection::make([
                    'uploads'
                ]))->implode(DIRECTORY_SEPARATOR);
                if(!$this->file->isDirectory(app_path($dictionary))) {
                    $this->file->makeDirectory(app_path($dictionary), 0777, true, true);
                }
                $file = Str::substr($hash, 12, 20) . '.' . $thumbImage->getClientOriginalExtension();
                if(!$this->file->exists($dictionary . DIRECTORY_SEPARATOR . $file)) {
                    $thumbImage->move($dictionary, $file);
                }
                $link->setAttribute('icon', $this->pathSplit($hash, '12,20', Collection::make([
                        'uploads'
                    ]))->implode('/') . '.' . $thumbImage->getClientOriginalExtension());
            }
        }
    }
}