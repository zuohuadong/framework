<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:40
 */
namespace Notadd\Foundation\Passport;
/**
 * Class TransientToken
 * @package Notadd\Foundation\Passport
 */
class TransientToken {
    /**
     * @param string $scope
     * @return bool
     */
    public function can($scope) {
        return true;
    }
    /**
     * @param string $scope
     * @return bool
     */
    public function cant($scope) {
        return false;
    }
    /**
     * @return bool
     */
    public function transient() {
        return true;
    }
}