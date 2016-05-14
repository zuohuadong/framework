<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 14:23
 */
namespace Notadd\Foundation\Image\Console;
use Illuminate\Console\Command;
use Notadd\Foundation\Image\Contracts\Cache;
/**
 * Class ImageCacheClearCommand
 * @package Notadd\Foundation\Image\Console
 */
class ImageCacheClearCommand extends Command {
    /**
     * @var string
     */
    protected $name = 'image:clearcache';
    /**
     * @var string
     */
    protected $description = 'Clear Image cache.';
    /**
     * ImageCacheClearCommand constructor.
     * @param \Notadd\Foundation\Image\Contracts\Cache $cache
     */
    public function __construct(Cache $cache) {
        parent::__construct();
        $this->cache = $cache;
    }
    /**
     * @return void
     */
    public function fire() {
        $this->cache->purge();
        $this->info('cache was successfully cleared');
    }
}