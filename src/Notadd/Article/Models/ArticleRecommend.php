<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-30 16:09
 */
namespace Notadd\Article\Models;
use Illuminate\Database\Eloquent\Model;
/**
 * Class ArticleRecommend
 * @package Notadd\Article\Models
 */
class ArticleRecommend extends Model {
    /**
     * @var array
     */
    protected $fillable = [
        'article_id',
        'position',
    ];
}