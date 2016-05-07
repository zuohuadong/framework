<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 15:14
 */
namespace Notadd\Menu\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Class MenuGroup
 * @package Notadd\Menu\Models
 */
class MenuGroup extends Model {
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'alias',
    ];
}