<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Menu\Observers;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Notadd\Menu\Models\Menu;
use Symfony\Component\HttpFoundation\File\UploadedFile;
class MenuItemObserver {
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
    public function updating(Menu $item) {
        $image = $item->getAttribute('icon_image');
        if($image) {
            if($image instanceof UploadedFile) {
                $hash = hash_file('md5', $image->getPathname(), false);
                $dictionary = $this->pathSplit($hash, '12', Collection::make([
                    'uploads'
                ]))->implode(DIRECTORY_SEPARATOR);
                if(!$this->file->isDirectory(app_path($dictionary))) {
                    $this->file->makeDirectory(app_path($dictionary), 0777, true, true);
                }
                $file = Str::substr($hash, 12, 20) . '.' . $image->getClientOriginalExtension();
                if(!$this->file->exists($dictionary . DIRECTORY_SEPARATOR . $file)) {
                    $image->move($dictionary, $file);
                }
                $item->setAttribute('icon_image', $this->pathSplit($hash, '12,20', Collection::make([
                        'uploads'
                    ]))->implode('/') . '.' . $image->getClientOriginalExtension());
            }
        }
    }
}