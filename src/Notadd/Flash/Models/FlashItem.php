<?php
/**
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.com
 */
namespace Notadd\Flash\Models;
use Notadd\Foundation\Database\Eloquent\Model;
/**
 * Class FlashItem
 * @package Notadd\Flash\Models
 */
class FlashItem extends Model {
    /**
     * @var array
     */
    protected $fillable = [
        'group_id',
        'title',
        'link',
        'link_target',
        'alt_info',
        'thumb_image',
        'full_image',
        'enabled',
    ];
    /**
     * @return \Notadd\Foundation\Database\Eloquent\Relations\BelongsTo
     */
    public function group() {
        return $this->belongsTo('App\Flash\Models\Flash', 'group_id');
    }
}