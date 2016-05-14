<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 15:20
 */
namespace Notadd\Foundation\Image;
/**
 * Class RecipeResolver
 * @package Notadd\Foundation\Image
 */
class RecipeResolver {
    /**
     * @var array
     */
    private $params;
    /**
     * @param array $params
     * @return mixed
     */
    public function __construct(array $params = []) {
        $this->params = $params;
    }
    /**
     * @param mixed $recipe
     * @return mixed
     */
    public function resolve($recipe) {
        if(isset($this->params[$recipe])) {
            $parameter = $this->params[$recipe];
            list($parameters, $filter) = array_pad(explode(',', str_replace(' ', null, $parameter)), 2, null);
            return compact('parameters', 'filter');
        }
    }
}