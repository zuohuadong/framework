<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-28 20:44
 */
namespace Notadd\Api;
use Notadd\Foundation\Abstracts\AbstractServiceProvider;
use Tobscure\JsonApi\ErrorHandler;
use Tobscure\JsonApi\Exception\Handler\FallbackExceptionHandler;
use Tobscure\JsonApi\Exception\Handler\InvalidParameterExceptionHandler;
/**
 * Class ApiServiceProvider
 * @package Notadd\Api
 */
class ApiServiceProvider extends AbstractServiceProvider {
    /**
     * @return void
     */
    public function boot() {
    }
    /**
     * @return void
     */
    public function register() {
        $this->app->singleton(ErrorHandler::class, function() {
            $handler = new ErrorHandler();
            $handler->registerHandler(new InvalidParameterExceptionHandler);
            $handler->registerHandler(new FallbackExceptionHandler($this->app->inDebugMode()));
            return $handler;
        });
    }
}