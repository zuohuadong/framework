<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-18 16:39
 */
namespace Notadd\Install\Controllers;
use Notadd\Foundation\Routing\Controller;
use Notadd\Install\Contracts\Prerequisite;
use Psr\Http\Message\ServerRequestInterface;
/**
 * Class PrerequisiteController
 * @package Notadd\Install\Controllers
 */
class PrerequisiteController extends Controller {
    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Notadd\Install\Contracts\Prerequisite $prerequisite
     * @return \Illuminate\Contracts\View\View
     */
    public function render(ServerRequestInterface $request, Prerequisite $prerequisite) {
        $view = $this->view->make('install::layout');
        $prerequisite->check();
        $errors = $prerequisite->getErrors();
        if(count($errors)) {
            $view->content = $this->view->make('install::errors');
            $view->content->errors = $errors;
        } else {
            $view->content = $this->view->make('install::install');
            if(extension_loaded('pdo_mysql')) {
                $view->content->has_mysql = true;
            } else {
                $view->content->has_mysql = false;
            }
            if(extension_loaded('pdo_pgsql')) {
                $view->content->has_pgsql = true;
            } else {
                $view->content->has_pgsql = false;
            }
            if(extension_loaded('pdo_mysql')) {
                $view->content->has_sqlite = true;
            } else {
                $view->content->has_sqlite = false;
            }
            if(extension_loaded("gd") && function_exists("imagewebp")) {
                $view->content->gd_trouble = false;
            } else {
                $view->content->gd_trouble = true;
            }
            if(extension_loaded("imagick")) {
                $imagick = new \Imagick();
                $formats = $imagick->queryFormats();
                if(in_array("WEBP", $formats) || in_array("webp", $formats)) {
                    $view->content->imagemagick_trouble = false;
                } else {
                    $view->content->imagemagick_trouble = true;
                }
            } else {
                $view->content->imagemagick_trouble = true;
            }
        }
        return $view;
    }
}