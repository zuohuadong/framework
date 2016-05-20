<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 17:14
 */
namespace Notadd\Image\Commands;
use Notadd\Image\Exceptions\NotSupportedException;
/**
 * Class ExifCommand
 * @package Notadd\Image\Commands
 */
class ExifCommand extends AbstractCommand {
    /**
     * @param \Notadd\Image\Image $image
     * @return bool
     */
    public function execute($image) {
        if(!function_exists('exif_read_data')) {
            throw new NotSupportedException("Reading Exif data is not supported by this PHP installation.");
        }
        $key = $this->argument(0)->value();
        // try to read exif data from image file
        $data = @exif_read_data($image->dirname . '/' . $image->basename);
        if(!is_null($key) && is_array($data)) {
            $data = array_key_exists($key, $data) ? $data[$key] : false;
        }
        $this->setOutput($data);
        return true;
    }
}