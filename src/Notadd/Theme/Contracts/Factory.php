<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-12-18 14:34
 */
namespace Notadd\Theme\Contracts;
/**
 * Interface Factory
 * @package Notadd\Theme\Contracts
 */
interface Factory {
    /**
     * @return mixed
     */
    public function getThemeList();
}