<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:49
 */
namespace Notadd\Foundation\Passport\Traits;
/**
 * Class FormatsScopesForStorage
 * @package Notadd\Foundation\Passport\Traits
 */
trait FormatsScopesForStorage {
    /**
     * @param array $scopes
     * @return string
     */
    public function formatScopesForStorage(array $scopes) {
        return json_encode(array_map(function ($scope) {
            return $scope->getIdentifier();
        }, $scopes));
    }
}