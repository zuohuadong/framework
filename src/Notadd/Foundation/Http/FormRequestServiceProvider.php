<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-17 11:21
 */
namespace Notadd\Foundation\Http;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Http\FormRequest;
use Notadd\Foundation\Traits\InjectEventsTrait;
use Symfony\Component\HttpFoundation\Request;
/**
 * Class FormRequestServiceProvider
 * @package Notadd\Foundation\Http
 */
class FormRequestServiceProvider extends ServiceProvider {
    use InjectEventsTrait;
    /**
     * @return void
     */
    public function register() {
    }
    /**
     * @return void
     */
    public function boot() {
        $this->getEvents()->listen(RouteMatched::class, function () {
            $this->app->resolving(function (FormRequest $request, $app) {
                $this->initializeRequest($request, $app['request']);
                $request->setContainer($app)->setRedirector($app['Illuminate\Routing\Redirector']);
            });
        });
    }
    /**
     * @param \Notadd\Foundation\Http\FormRequest $form
     * @param \Symfony\Component\HttpFoundation\Request $current
     * @return void
     */
    protected function initializeRequest(FormRequest $form, Request $current) {
        $files = $current->files->all();
        $files = is_array($files) ? array_filter($files) : $files;
        $form->initialize($current->query->all(), $current->request->all(), $current->attributes->all(), $current->cookies->all(), $files, $current->server->all(), $current->getContent());
        if($session = $current->getSession()) {
            $form->setSession($session);
        }
        $form->setUserResolver($current->getUserResolver());
        $form->setRouteResolver($current->getRouteResolver());
    }
}