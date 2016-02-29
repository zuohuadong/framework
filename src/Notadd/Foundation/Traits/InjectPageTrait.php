<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-02-29 15:31
 */
namespace Notadd\Foundation\Traits;
use Illuminate\Container\Container;
/**
 * Class InjectPageTrait
 * @package Notadd\Foundation\Traits
 */
trait InjectPageTrait {
    /**
     * @var \Notadd\Page\Factory
     */
    private $page;
    /**
     * @return \Notadd\Page\Factory
     */
    public function getPage() {
        if(!isset($this->page)) {
            $this->page = Container::getInstance()->make('page');
        }
        return $this->page;
    }
}