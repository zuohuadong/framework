<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 15:06
 */
namespace Notadd\Foundation\Image\Responses;
use DateTime;
use Notadd\Foundation\Image\Contracts\Image;
use Symfony\Component\HttpFoundation\Response;
/**
 * Class GenericFileResponse
 * @package Notadd\Foundation\Image\Responses
 */
class GenericFileResponse extends AbstractFileResponse {
    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Notadd\Foundation\Image\Contracts\Image $image
     * @param \DateTime $lastMod
     */
    protected function setHeaders(Response $response, Image $image, DateTime $lastMod) {
        $response->headers->set('Content-type', $image->getMimeType());
        $response->setContent($content = $image->getContents());
        $response->setLastModified($lastMod);
        $response->setEtag(hash('md5', $response->getContent()));
        $response->headers->set('Accept-ranges', 'bytes');
        $response->headers->set('Keep-Alive', 'timeout=15, max=200');
        $response->headers->set('Connection', 'Keep-Alive', true);
    }
    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \DateTime $lastMod
     * @return void
     */
    protected function setHeadersIfNotProcessed(Response $response, DateTime $lastMod) {
        $response->setNotModified();
        $response->setLastModified($lastMod);
    }
}