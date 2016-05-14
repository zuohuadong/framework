<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 11:49
 */
namespace Notadd\Foundation\Image\Contracts;
interface FileResponse {
    /**
     * @param \Notadd\Foundation\Image\Contracts\Image $image
     */
    public function make(Image $image);
    /**
     * @return void
     */
    public function send();
    /**
     * @param int $status
     * @return void
     */
    public function abort($status = 404);
    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return void
     */
    public function notFound();
}