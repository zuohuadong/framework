<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 15:03
 */
namespace Notadd\Foundation\Image\Responses;
use DateTime;
use Notadd\Foundation\Image\Caches\CachedImage;
use Notadd\Foundation\Image\Contracts\FileResponse;
use Notadd\Foundation\Image\Contracts\Image;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
/**
 * Class AbstractFileResponse
 * @package Notadd\Foundation\Image\Responses
 */
abstract class AbstractFileResponse implements FileResponse {
    /**
     * @var mixed
     */
    protected $headers = [];
    /**
     * @var mixed
     */
    protected $request = [];
    /**
     * @var mixed
     */
    protected $response;
    /**
     * @param Request $request
     * @access public
     * @return mixed
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }
    /**
     * @param \Notadd\Foundation\Image\Contracts\Image $image
     */
    final public function make(Image $image) {
        $this->response = new Response(null, 200);
        $this->response->setPublic();
        $lastMod = (new DateTime)->setTimestamp($modDate = $image->getLastModTime());
        $mod = strtotime($this->request->headers->get('if-modified-since', time()));
        if(($image instanceof CachedImage || !$image->isProcessed()) && $mod === $modDate) {
            $this->setHeadersIfNotProcessed($this->response, $lastMod);
        } else {
            $this->setHeaders($this->response, $image, $lastMod);
        }
    }
    /**
     * @return Response
     */
    public function getResponse() {
        return $this->response;
    }
    /**
     * @return mixed
     */
    public function send() {
        if(!isset($this->response)) {
            throw new RuntimeException('response not created yet. Create a response before calling send.');
        }
        return $this->response->send();
    }
    /**
     * @param Response $response
     * @param Image $image
     * @param \DateTime $lastMod
     */
    abstract protected function setHeaders(Response $response, Image $image, DateTime $lastMod);
    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \DateTime $lastMod
     * @return mixed
     */
    abstract protected function setHeadersIfNotProcessed(Response $response, DateTime $lastMod);
    /**
     * @param int $status
     */
    public function abort($status = 404) {
        $response = new Response(null, $status);
        $response->send();
    }
    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function notFound() {
        throw new NotFoundHttpException;
    }
    /**
     * @param mixed $method
     * @param mixed $arguments
     * @return mixed
     */
    public function __call($method, $arguments) {
        return call_user_func_array([
            $this->response,
            $method
        ], $arguments);
    }
}