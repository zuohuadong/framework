<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-10 11:30
 */
require '../vendor/autoload.php';
(new \Notadd\Foundation\Api\Server(realpath(__DIR__ . '/../')))->listen();