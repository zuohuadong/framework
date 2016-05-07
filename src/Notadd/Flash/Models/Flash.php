<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Flash\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Class Flash
 * @package Notadd\Flash\Models
 */
class Flash extends Model {
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'alias',
    ];
}