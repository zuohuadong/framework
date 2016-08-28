<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 17:18
 */
namespace Notadd\Install\Prerequisite;
use Notadd\Install\Abstracts\AbstractPrerequisite;
/**
 * Class WritablePath
 * @package Notadd\Install\Prerequisite
 */
class WritablePath extends AbstractPrerequisite {
    /**
     * @var array
     */
    protected $paths;
    /**
     * WritablePath constructor.
     * @param array $paths
     */
    public function __construct(array $paths) {
        $this->paths = $paths;
    }
    /**
     * @return void
     */
    public function check() {
        foreach($this->paths as $path) {
            if(!is_writable($path)) {
                $this->errors[] = [
                    'message' => 'The ' . realpath($path) . ' directory is not writable.',
                    'detail' => 'Please chmod this directory' . ($path !== public_path() ? ' and its contents' : '') . ' to 0775.'
                ];
            }
        }
    }
}