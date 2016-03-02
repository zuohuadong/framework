<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-16 22:21
 */
namespace Notadd\Foundation\Exceptions;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Psr\Log\LoggerInterface;
use Illuminate\Http\Response;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
/**
 * Class Handler
 * @package Notadd\Foundation\Exceptions
 */
class Handler implements ExceptionHandlerContract {
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;
    /**
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];
    /**
     * @param \Psr\Log\LoggerInterface $log
     */
    public function __construct(LoggerInterface $log) {
        $this->log = $log;
    }
    /**
     * @param \Exception $e
     * @return void
     */
    public function report(Exception $e) {
        if($this->shouldReport($e)) {
            $this->log->error($e);
        }
    }
    /**
     * @param \Exception $e
     * @return bool
     */
    public function shouldReport(Exception $e) {
        return !$this->shouldntReport($e);
    }
    /**
     * @param \Exception $e
     * @return bool
     */
    protected function shouldntReport(Exception $e) {
        $dontReport = array_merge($this->dontReport, [HttpResponseException::class]);
        foreach($dontReport as $type) {
            if($e instanceof $type) {
                return true;
            }
        }
        return false;
    }
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e) {
        if($e instanceof HttpResponseException) {
            return $e->getResponse();
        } elseif($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        } elseif($e instanceof AuthorizationException) {
            $e = new HttpException(403, $e->getMessage());
        } elseif($e instanceof ValidationException && $e->getResponse()) {
            return $e->getResponse();
        }
        if($this->isHttpException($e)) {
            return $this->toIlluminateResponse($this->renderHttpException($e), $e);
        } else {
            return $this->toIlluminateResponse($this->convertExceptionToResponse($e), $e);
        }
    }
    /**
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Exception $e
     * @return \Illuminate\Http\Response
     */
    protected function toIlluminateResponse($response, Exception $e) {
        $response = new Response($response->getContent(), $response->getStatusCode(), $response->headers->all());
        $response->exception = $e;
        return $response;
    }
    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Exception $e
     * @return void
     */
    public function renderForConsole($output, Exception $e) {
        (new ConsoleApplication)->renderException($e, $output);
    }
    /**
     * @param \Symfony\Component\HttpKernel\Exception\HttpException $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $e) {
        $status = $e->getStatusCode();
        if(view()->exists("errors.{$status}")) {
            return response()->view("errors.{$status}", ['exception' => $e], $status, $e->getHeaders());
        } else {
            return $this->convertExceptionToResponse($e);
        }
    }
    /**
     * @param \Exception $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertExceptionToResponse(Exception $e) {
        $e = FlattenException::create($e);
        $handler = new SymfonyExceptionHandler(config('app.debug'));
        $decorated = $this->decorate($handler->getContent($e), $handler->getStylesheet($e));
        return SymfonyResponse::create($decorated, $e->getStatusCode(), $e->getHeaders());
    }
    /**
     * @param $content
     * @param $css
     * @return string
     */
    protected function decorate($content, $css) {
        return <<<EOF
<!DOCTYPE html>
<html>
    <head>
        <meta name="robots" content="noindex,nofollow" />
        <style>
            /* Copyright (c) 2010, Yahoo! Inc. All rights reserved. Code licensed under the BSD License: http://developer.yahoo.com/yui/license.html */
            html{color:#000;background:#FFF;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}q:before,q:after{content:'';}abbr,acronym{border:0;font-variant:normal;}sup{vertical-align:text-top;}sub{vertical-align:text-bottom;}input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;}input,textarea,select{*font-size:100%;}legend{color:#000;}
            html { background: #eee; padding: 10px }
            img { border: 0; }
            #sf-resetcontent { width:970px; margin:0 auto; }
            $css
        </style>
    </head>
    <body>
        $content
    </body>
</html>
EOF;
    }
    /**
     * @param \Exception $e
     * @return bool
     */
    protected function isHttpException(Exception $e) {
        return $e instanceof HttpException;
    }
}