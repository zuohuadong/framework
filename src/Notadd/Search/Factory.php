<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-24 13:53
 */
namespace Notadd\Search;
use Notadd\Setting\Factory as SettingFactory;
/**
 * Class Factory
 * @package Notadd\Search
 */
class Factory {
    /**
     * @var \Notadd\Setting\Factory
     */
    protected $setting;
    /**
     * Factory constructor.
     * @param \Notadd\Setting\Factory $setting
     */
    public function __construct(SettingFactory $setting) {
        $this->setting = $setting;
    }
    /**
     * @param $engine
     * @return string
     */
    public function export($engine) {
        switch($engine) {
            case 'baidu':
                if($this->setting->get('search.baidu.zhannei', false)) {
                    return $this->setting->get('search.baidu.zhannei.code', '');
                } else {
                    return '';
                }
            break;
        }
        return '未找到任何搜索引擎！';
    }
}