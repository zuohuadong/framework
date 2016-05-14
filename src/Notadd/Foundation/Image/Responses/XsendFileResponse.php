<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 15:07
 */
namespace Notadd\Foundation\Image\Responses;
use DateTime;
use Notadd\Foundation\Image\Contracts\Image;
use Symfony\Component\HttpFoundation\Response;
class XsendFileResponse extends GenericFileResponse {
    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Notadd\Foundation\Image\Contracts\Image $image
     * @param \DateTime $lastMod
     */
    protected function setHeaders(Response $response, Image $image, DateTime $lastMod) {
        $response->headers->set('Content-type', $image->getMimeType());
        $response->setLastModified($lastMod);
        $response->headers->set('Accept-ranges', 'bytes');
        $response->headers->set('Keep-Alive', 'timeout=5, max=99');
        $response->headers->set('Connection', 'keep-alive', true);
        if($image->isProcessed()) {
            $response->setContent($content = $image->getContents());
            $response->setEtag(hash('md5', $content));
        } else {
            $file = $image->getSource();
            $response->setEtag(md5_file($file));
            $response->headers->set('Content-Length', filesize($file));
            $response->headers->set('Content-Disposition', sprintf('inline; filename="%s"', basename($file)));
            $response->headers->set('X-Sendfile', $file);
        }
    }
    /**
     * @return void
     */
    public function send() {
        $this->response->send();
    }
}