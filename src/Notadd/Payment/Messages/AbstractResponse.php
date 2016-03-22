<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-11 16:54
 */
namespace Notadd\Payment\Messages;
use Notadd\Payment\Contracts\RedirectResponse;
use Notadd\Payment\Contracts\Request as RequestContract;
use Notadd\Payment\Contracts\Response as ResponseContract;
use Notadd\Payment\Exceptions\RuntimeException;
use Symfony\Component\HttpFoundation\RedirectResponse as HttpRedirectResponse;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
/**
 * Class AbstractResponse
 * @package Notadd\Payment\Messages
 */
abstract class AbstractResponse implements ResponseContract {
    /**
     * @var \Notadd\Payment\Contracts\Response
     */
    protected $request;
    /**
     * @var mixed
     */
    protected $data;
    /**
     * AbstractResponse constructor.
     * @param \Notadd\Payment\Contracts\Request $request
     * @param $data
     */
    public function __construct(RequestContract $request, $data) {
        $this->request = $request;
        $this->data = $data;
    }
    /**
     * @return \Notadd\Payment\Contracts\Request
     */
    public function getRequest() {
        return $this->request;
    }
    /**
     * @return bool
     */
    public function isPending() {
        return false;
    }
    /**
     * @return boolean
     */
    public function isRedirect() {
        return false;
    }
    /**
     * @return boolean
     */
    public function isTransparentRedirect() {
        return false;
    }
    /**
     * @return boolean
     */
    public function isCancelled() {
        return false;
    }
    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }
    /**
     * @return null|string
     */
    public function getMessage() {
        return null;
    }
    /**
     * @return null|string
     */
    public function getCode() {
        return null;
    }
    /**
     * @return null|string
     */
    public function getTransactionReference() {
        return null;
    }
    /**
     * @return string
     */
    public function getTransactionId() {
        return null;
    }
    /**
     * @return void
     */
    public function redirect() {
        $this->getRedirectResponse()->send();
        exit;
    }
    /**
     * @return HttpRedirectResponse
     */
    public function getRedirectResponse() {
        if(!$this instanceof RedirectResponse || !$this->isRedirect()) {
            throw new RuntimeException('This response does not support redirection.');
        }
        if('GET' === $this->getRedirectMethod()) {
            return HttpRedirectResponse::create($this->getRedirectUrl());
        } elseif('POST' === $this->getRedirectMethod()) {
            $hiddenFields = '';
            foreach($this->getRedirectData() as $key => $value) {
                $hiddenFields .= sprintf('<input type="hidden" name="%1$s" value="%2$s" />', htmlentities($key, ENT_QUOTES, 'UTF-8', false), htmlentities($value, ENT_QUOTES, 'UTF-8', false)) . "\n";
            }
            $output = '<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Redirecting...</title>
    </head>
    <body onload="document.forms[0].submit();">
        <form action="%1$s" method="post">
            <p>Redirecting to payment page...</p>
            <p>
                %2$s
                <input type="submit" value="Continue" />
            </p>
        </form>
    </body>
</html>';
            $output = sprintf($output, htmlentities($this->getRedirectUrl(), ENT_QUOTES, 'UTF-8', false), $hiddenFields);
            return HttpResponse::create($output);
        }
        throw new RuntimeException('Invalid redirect method "' . $this->getRedirectMethod() . '".');
    }
}
