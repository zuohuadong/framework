<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-27 19:29
 */
namespace Notadd\Install\Prerequisite;
use Notadd\Install\Abstracts\AbstractPrerequisite;
/**
 * Class PhpExtension
 * @package Notadd\Install\Prerequisite
 */
class PhpExtension extends AbstractPrerequisite {
    /**
     * @var array
     */
    protected $extensions;
    /**
     * PhpExtension constructor.
     * @param array $extensions
     */
    public function __construct(array $extensions) {
        $this->extensions = $extensions;
    }
    /**
     * @return void
     */
    public function check() {
        foreach($this->extensions as $extension) {
            if(!extension_loaded($extension)) {
                $this->errors[] = [
                    'message' => "The PHP extension '{$extension}' is required.",
                    'detail' => "The PHP extension '{$extension}' is required.",
                ];
            }
        }
    }
}