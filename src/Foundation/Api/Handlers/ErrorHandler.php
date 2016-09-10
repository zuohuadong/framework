<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-10 11:54
 */
namespace Notadd\Foundation\Api\Handlers;
use Exception;
use Notadd\Foundation\Api\Responses\JsonResponse;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\ErrorHandler as ApiErrorHandler;
/**
 * Class ErrorHandler
 * @package Notadd\Foundation\Api\Handlers
 */
class ErrorHandler {
    /**
     * @var \Tobscure\JsonApi\ErrorHandler
     */
    protected $errorHandler;
    /**
     * ErrorHandler constructor.
     * @param \Tobscure\JsonApi\ErrorHandler $handler
     */
    public function __construct(ApiErrorHandler $handler) {
        $this->errorHandler = $handler;
    }
    /**
     * @param \Exception $e
     * @return \Notadd\Foundation\Api\Responses\JsonResponse
     */
    public function handle(Exception $e) {
        $response = $this->errorHandler->handle($e);
        $document = new Document;
        $document->setErrors($response->getErrors());
        return new JsonResponse($document, $response->getStatus());
    }
}