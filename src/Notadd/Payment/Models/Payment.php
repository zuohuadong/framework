<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-19 21:17
 */
namespace Notadd\Payment\Models;
use Notadd\Foundation\Database\Eloquent\Model;
class Payment extends Model {
    /**
     * @var array
     */
    protected $fillable = [
        'subject',
        'trade_number',
        'type',
        'total_fee',
        'is_success',
        'data',
    ];
}