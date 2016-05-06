<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-06 15:29
 */
namespace Notadd\Category\Observers;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Notadd\Category\Models\Category;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class CategoryObserver {
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
     * @param \Notadd\Category\Models\Category $category
     */
    public function creating(Category $category) {
        $backgroundImage = $category->getAttribute('background_image');
        if($backgroundImage) {
            if($backgroundImage instanceof UploadedFile) {
                $hash = hash_file('md5', $backgroundImage->getPathname(), false);
                $dictionary = $this->pathSplit($hash, '12', Collection::make([
                    'uploads'
                ]))->implode(DIRECTORY_SEPARATOR);
                if(!$this->file->isDirectory(app_path($dictionary))) {
                    $this->file->makeDirectory(app_path($dictionary), 0777, true, true);
                }
                $file = Str::substr($hash, 12, 20) . '.' . $backgroundImage->getClientOriginalExtension();
                if(!$this->file->exists($dictionary . DIRECTORY_SEPARATOR . $file)) {
                    $backgroundImage->move($dictionary, $file);
                }
                $category->setAttribute('background_image', $this->pathSplit($hash, '12,20', Collection::make([
                        'uploads'
                    ]))->implode('/') . '.' . $backgroundImage->getClientOriginalExtension());
            }
        }
        $topImage = $category->getAttribute('top_image');
        if($topImage) {
            if($topImage instanceof UploadedFile) {
                $hash = hash_file('md5', $topImage->getPathname(), false);
                $dictionary = $this->pathSplit($hash, '12', Collection::make([
                    'uploads'
                ]))->implode(DIRECTORY_SEPARATOR);
                if(!$this->file->isDirectory(app_path($dictionary))) {
                    $this->file->makeDirectory(app_path($dictionary), 0777, true, true);
                }
                $file = Str::substr($hash, 12, 20) . '.' . $topImage->getClientOriginalExtension();
                if(!$this->file->exists($dictionary . DIRECTORY_SEPARATOR . $file)) {
                    $topImage->move($dictionary, $file);
                }
                $category->setAttribute('top_image', $this->pathSplit($hash, '12,20', Collection::make([
                        'uploads'
                    ]))->implode('/') . '.' . $topImage->getClientOriginalExtension());
            }
        }
    }
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
     * @param \Notadd\Category\Models\Category $category
     */
    public function updating(Category $category) {
        $backgroundImage = $category->getAttribute('background_image');
        if($backgroundImage) {
            if($backgroundImage instanceof UploadedFile) {
                $hash = hash_file('md5', $backgroundImage->getPathname(), false);
                $dictionary = $this->pathSplit($hash, '12', Collection::make([
                    'uploads'
                ]))->implode(DIRECTORY_SEPARATOR);
                if(!$this->file->isDirectory(app_path($dictionary))) {
                    $this->file->makeDirectory(app_path($dictionary), 0777, true, true);
                }
                $file = Str::substr($hash, 12, 20) . '.' . $backgroundImage->getClientOriginalExtension();
                if(!$this->file->exists($dictionary . DIRECTORY_SEPARATOR . $file)) {
                    $backgroundImage->move($dictionary, $file);
                }
                $category->setAttribute('background_image', $this->pathSplit($hash, '12,20', Collection::make([
                        'uploads'
                    ]))->implode('/') . '.' . $backgroundImage->getClientOriginalExtension());
            }
        }
        $topImage = $category->getAttribute('top_image');
        if($topImage) {
            if($topImage instanceof UploadedFile) {
                $hash = hash_file('md5', $topImage->getPathname(), false);
                $dictionary = $this->pathSplit($hash, '12', Collection::make([
                    'uploads'
                ]))->implode(DIRECTORY_SEPARATOR);
                if(!$this->file->isDirectory(app_path($dictionary))) {
                    $this->file->makeDirectory(app_path($dictionary), 0777, true, true);
                }
                $file = Str::substr($hash, 12, 20) . '.' . $topImage->getClientOriginalExtension();
                if(!$this->file->exists($dictionary . DIRECTORY_SEPARATOR . $file)) {
                    $topImage->move($dictionary, $file);
                }
                $category->setAttribute('top_image', $this->pathSplit($hash, '12,20', Collection::make([
                        'uploads'
                    ]))->implode('/') . '.' . $topImage->getClientOriginalExtension());
            }
        }
    }
}