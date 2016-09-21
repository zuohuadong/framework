<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-20 11:25
 */
namespace Notadd\Foundation\Routing;
use Illuminate\Container\Container;
use Illuminate\Contracts\Routing\UrlRoutable;
use Psr\Http\Message\ServerRequestInterface;
/**
 * Class UrlGenerator
 * @package Notadd\Foundation\Routing
 */
class UrlGenerator {
    /**
     * @var string|null
     */
    protected $cachedScheme;
    /**
     * @var string|null
     */
    protected $cachedRoot;
    /**
     * @var \Illuminate\Container\Container
     */
    protected $container;
    /**
     * @var string|null
     */
    protected $forceSchema;
    /**
     * UrlGenerator constructor.
     * @param \Illuminate\Container\Container $container
     */
    public function __construct(Container $container) {
        $this->container = $container;
    }
    /**
     * @param string $path
     * @param array $extra
     * @param bool $secure
     * @return string
     */
    public function to($path, $extra = [], $secure = null) {
        if($this->isValidUrl($path)) {
            return $path;
        }
        $scheme = $this->getSchemeForUrl($secure);
        $extra = $this->formatParametersForUrl($extra);
        $tail = implode('/', array_map('rawurlencode', (array)$extra));
        $root = $this->getRootUrl($scheme);
        return $this->trimUrl($root, $path, $tail);
    }
    /**
     * @param string $path
     * @return bool
     */
    protected function isValidUrl($path) {
        if(starts_with($path, [
            '#',
            '//',
            'mailto:',
            'tel:',
            'http://',
            'https://'
        ])) {
            return true;
        }
        return filter_var($path, FILTER_VALIDATE_URL) !== false;
    }
    /**
     * @param bool|null $secure
     * @return null|string
     */
    protected function getSchemeForUrl($secure) {
        if(is_null($secure)) {
            if(is_null($this->cachedScheme)) {
                $this->cachedScheme = $this->container->make(ServerRequestInterface::class)->getUri()->getScheme() . '://';
            }
            return $this->cachedScheme;
        }
        return $secure ? 'https://' : 'http://';
    }
    /**
     * @param mixed|array $parameters
     * @return array
     */
    protected function formatParametersForUrl($parameters) {
        return $this->replaceRoutableParametersForUrl($parameters);
    }
    /**
     * @param array $parameters
     * @return array
     */
    protected function replaceRoutableParametersForUrl($parameters = []) {
        $parameters = is_array($parameters) ? $parameters : [$parameters];
        foreach($parameters as $key => $parameter) {
            if($parameter instanceof UrlRoutable) {
                $parameters[$key] = $parameter->getRouteKey();
            }
        }
        return $parameters;
    }
    /**
     * Get the base URL for the request.
     * @param string $scheme
     * @param string $root
     * @return string
     */
    protected function getRootUrl($scheme, $root = null) {
        if(is_null($root)) {
            if(is_null($this->cachedRoot)) {
                $this->cachedRoot = $this->container->make('request')->root();
            }
            $root = $this->cachedRoot;
        }
        $start = starts_with($root, 'http://') ? 'http://' : 'https://';
        return preg_replace('~' . $start . '~', $scheme, $root, 1);
    }
    /**
     * @param string $root
     * @param string $path
     * @param string $tail
     * @return string
     */
    protected function trimUrl($root, $path, $tail = '') {
        return trim($root . '/' . trim($path . '/' . $tail, '/'), '/');
    }
    /**
     * Generate a secure, absolute URL to the given path.
     * @param string $path
     * @param array $parameters
     * @return string
     */
    public function secure($path, $parameters = []) {
        return $this->to($path, $parameters, true);
    }
    /**
     * Generate a URL to an application asset.
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    public function asset($path, $secure = null) {
        if($this->isValidUrl($path)) {
            return $path;
        }
        // Once we get the root URL, we will check to see if it contains an index.php
        // file in the paths. If it does, we will remove it since it is not needed
        // for asset paths, but only for routes to endpoints in the application.
        $root = $this->getRootUrl($this->getScheme($secure));
        return $this->removeIndex($root) . '/' . trim($path, '/');
    }
    /**
     * Generate a URL to an application asset from a root domain such as CDN etc.
     * @param string $root
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    public function assetFrom($root, $path, $secure = null) {
        // Once we get the root URL, we will check to see if it contains an index.php
        // file in the paths. If it does, we will remove it since it is not needed
        // for asset paths, but only for routes to endpoints in the application.
        $root = $this->getRootUrl($this->getScheme($secure), $root);
        return $this->removeIndex($root) . '/' . trim($path, '/');
    }
    /**
     * Remove the index.php file from a path.
     * @param string $root
     * @return string
     */
    protected function removeIndex($root) {
        $i = 'index.php';
        return str_contains($root, $i) ? str_replace('/' . $i, '', $root) : $root;
    }
    /**
     * Generate a URL to a secure asset.
     * @param string $path
     * @return string
     */
    public function secureAsset($path) {
        return $this->asset($path, true);
    }
    /**
     * Get the scheme for a raw URL.
     * @param bool|null $secure
     * @return string
     */
    protected function getScheme($secure) {
        if(is_null($secure)) {
            return $this->forceSchema ?: $this->container->make(ServerRequestInterface::class)->getUri()->getScheme() . '://';
        }
        return $secure ? 'https://' : 'http://';
    }
}