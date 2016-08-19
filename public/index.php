<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-19 22:45
 */
require '../vendor/autoload.php';
(new Notadd\Foundation\Http\Server(realpath(__DIR__ . '/../')))->listen();