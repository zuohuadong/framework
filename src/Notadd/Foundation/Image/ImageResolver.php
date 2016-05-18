<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 15:13
 */
namespace Notadd\Foundation\Image;
use Notadd\Foundation\Image\Contracts\Cache;
use Notadd\Foundation\Image\Contracts\Image as ImageContract;
use Notadd\Foundation\Image\Contracts\Resolver;
use Notadd\Foundation\Image\Contracts\ResolverConfiguration;
/**
 * Class ImageResolver
 * @package Notadd\Foundation\Image
 */
class ImageResolver implements Resolver {
    /**
     * @var \Notadd\Foundation\Image\Contracts\ResolverConfiguration
     */
    protected $config;
    /**
     * @var \Notadd\Foundation\Image\Contracts\Image
     */
    protected $image;
    /**
     * @var array
     */
    protected $input = [];
    /**
     * @var mixed
     */
    protected $parameter = [];
    /**
     * @var mixed
     */
    protected $processCache;
    /**
     * @var mixed
     */
    protected $cachedNames;
    /**
     * ImageResolver constructor.
     * @param \Notadd\Foundation\Image\Contracts\ResolverConfiguration $config
     * @param \Notadd\Foundation\Image\Contracts\Image $image
     * @param \Notadd\Foundation\Image\Contracts\Cache $cache
     */
    public function __construct(ResolverConfiguration $config, ImageContract $image, Cache $cache) {
        $this->image = $image;
        $this->config = $config;
        $this->processCache = $cache;
    }
    /**
     * @param string $base
     * @return bool|void
     */
    public function setResolveBase($base = '/') {
        return $this->config->set('base', $base);
    }
    /**
     * @param string $parameter
     */
    public function setParameter($parameter) {
        $this->input['parameter'] = $parameter;
    }
    /**
     * @param string $source
     */
    public function setSource($source) {
        $this->input['source'] = $source;
    }
    /**
     * @param null $filter
     */
    public function setFilter($filter = null) {
        $this->input['filter'] = $filter;
    }
    /**
     * @param null $key
     * @return mixed|null
     */
    public function getParameter($key = null) {
        if(is_null($key)) {
            return $this->parameter;
        }
        return isset($this->parameter[$key]) ? $this->parameter[$key] : null;
    }
    /**
     * @return bool|mixed|\Notadd\Foundation\Image\Contracts\Image|string
     */
    public function resolve() {
        $this->image->close();
        if(!$this->canResolve()) {
            return false;
        }
        $this->parseAll();
        if($this->config->cache && $image = $this->resolveFromCache($id = $this->getImageRequestId($this->getInputQuery(), $this->input['source']))) {
            return $image;
        }
        if(!$img = $this->isReadableFile($this->parameter)) {
            return false;
        }
        if(!$this->image->load($img)) {
            return false;
        }
        $this->image->process($this);
        if($this->config->cache) {
            $this->processCache->put($id, $this->image->getContents());
        }
        return $this->image;
    }
    /**
     * @return void
     */
    public function close() {
        $this->input = [];
        $this->parameter = [];
    }
    /**
     * @return bool|mixed|string|void
     */
    public function getCached() {
        if(!$this->canResolve()) {
            return false;
        }
        if(!$this->config->cache) {
            return false;
        }
        $this->resolve();
        return $this->resolveFromCache($this->getImageRequestId($this->getInputQuery(), $this->input['source']));
    }
    /**
     * @param \Notadd\Foundation\Image\Contracts\Image $cachedImage
     * @return string
     */
    public function getCachedUrl(ImageContract $cachedImage) {
        return sprintf('/%s/%s', $this->config->cache_route, $this->processCache->getRelPath($cachedImage->getSource()));
    }
    /**
     * @param \Notadd\Foundation\Image\Contracts\Image $image
     * @return string
     */
    public function getImageUrl(ImageContract $image) {
        $base = substr($image->getSource(), strlen($this->config->base));
        $input = $this->input;
        return sprintf('/%s', trim(implode('/', [
                $this->config->base_route,
                $input['parameter'],
                trim($base, '/'),
                $input['filter']
            ]), '/'));
    }
    /**
     * @param mixed $id
     * @return bool|mixed|string|void
     */
    public function resolveFromCache($id) {
        $id = preg_replace('~(\.(jpe?g|gif|png|webp))$~', null, $this->processCache->getIdFromUrl($id));
        if($this->processCache->has($id)) {
            //$image->close();
            $image = $this->processCache->get($id);
            return $image;
        }
        return false;
    }
    /**
     * @return void
     */
    public function disableCache() {
        $this->config->set('cache', false);
    }
    /**
     * @return bool
     */
    protected function canResolve() {
        return is_array($this->input) && array_key_exists('parameter', $this->input) && array_key_exists('source', $this->input) && array_key_exists('filter', $this->input);
    }
    /**
     * @return mixed
     */
    protected function parseAll() {
        if(!empty($this->parameter)) {
            return $this->parameter;
        }
        $this->parseParameter();
        $this->parseSource();
        $this->parseFilter();
    }
    /**
     * @return string
     */
    protected function getInputQuery() {
        return implode('/', array_values($this->input));
    }
    /**
     * @return mixed
     */
    protected function parseParameter() {
        list ($mode, $width, $height, $gravity, $background) = array_pad(preg_split('%/%', $this->input['parameter'], -1, PREG_SPLIT_NO_EMPTY), 5, null);
        return $this->setParameterValues((int)$mode, ((int)$mode !== 1 && (int)$mode !== 2) ? $this->getIntVal($width) : (int)$this->getIntVal($width), ((int)$mode !== 1 && (int)$mode !== 2) ? $this->getIntVal($height) : (int)$this->getIntVal($height), $this->getIntVal($gravity), $background);
    }
    /**
     * @param $mode
     * @param null $width
     * @param null $height
     * @param null $gravity
     * @param null $background
     */
    protected function setParameterValues($mode, $width = null, $height = null, $gravity = null, $background = null) {
        $parameter = compact('mode', 'width', 'height', 'gravity', 'background');
        $this->parameter = array_merge($this->parameter, $parameter);
    }
    /**
     * @return void
     */
    protected function parseSource() {
        $this->parameter['source'] = $this->input['source'];
    }
    /**
     * @return void
     */
    protected function parseFilter() {
        if(isset($this->input['filter'])) {
            $fragments = preg_split('%:%', $this->input['filter'], -1, PREG_SPLIT_NO_EMPTY);
            $this->parameter['filter'] = $this->parseImageFilter($fragments);
            return;
        }
        $this->parameter['filter'] = [];
    }
    /**
     * @param null $value
     * @return int|null
     */
    protected function getIntVal($value = null) {
        return is_null($value) ? $value : (int)$value;
    }
    /**
     * @param $source
     * @param array $parameter
     */
    protected function getImageSource($source, array &$parameter) {
        $fragments = preg_split('%:%', $source, -1, PREG_SPLIT_NO_EMPTY);
        $parameter['source'] = array_shift($fragments);
        $this->parseImageFilter($fragments, $parameter);
    }
    /**
     * @param array $filters
     * @return array|void
     */
    protected function parseImageFilter(array $filters) {
        $parameter = [];
        if('filter' !== array_shift($filters)) {
            return;
        }
        foreach($filters as $filter) {
            $this->getFilterParams($parameter, $filter);
        }
        return $parameter;
    }
    /**
     * @param array $filters
     * @param $filter
     */
    protected function getFilterParams(array &$filters, $filter) {
        $fragments = preg_split('%;%', $filter, -1, PREG_SPLIT_NO_EMPTY);
        $name = array_shift($fragments);
        $params = [];
        foreach($fragments as $param) {
            list($key, $value) = explode('=', $param);
            $params[$key] = $value;
        }
        $filters[$name] = $params;
    }
    /**
     * @param array $parameter
     */
    protected function getOptionalColor(array &$parameter) {
        preg_match('/^[0-9A-Fa-f]{3,6}/', $parameter['source'], $color);
        $length = strpos($parameter['source'], '/');
        $hasColor = (6 === $length && 3 === $length) && $length === strlen(current($color));
        if(!empty($color)) {
            $parameter['source'] = substr($parameter['source'], strlen(current($color)));
        }
        if($hasColor) {
            $parameter['background'] = '#' . current($color);
        }
    }
    /**
     * @param $requestString
     * @param null $source
     * @return mixed
     */
    protected function getImageRequestId($requestString, $source = null) {
        if(!isset($this->cachedNames[$requestString])) {
            $this->cachedNames[$requestString] = $this->processCache->createKey($source, $requestString, $this->config->cache_prefix, pathinfo($source, PATHINFO_EXTENSION));
        }
        return $this->cachedNames[$requestString];
    }
    /**
     * @param \Notadd\Foundation\Image\ImageInterface $image
     * @param $requestString
     * @param $source
     * @return mixed
     */
    protected function getProcessedCacheId(ImageInterface $image, $requestString, $source) {
        $osuffix = $image->getSourceFormat();
        $psuffix = $image->getFileFormat();
        unset($this->cachedNames[$requestString]);
        $this->cachedNames[$requestString] = $this->processCache->createKey($source . $osuffix . $psuffix, $requestString, $this->config->cache_prefix, $psuffix);
        return $this->cachedNames[$requestString];
    }
    /**
     * @param $source
     * @return mixed
     */
    protected function getOutputTypeFromFilter($source) {
        if(($filter = $this->getParameter('filter')) && isset($filter[$format = $this->config->format_filter])) {
            return current(array_values($filter[$format]));
        }
        return pathinfo($source, PATHINFO_EXTENSION);
    }
    /**
     * @param array $parameter
     * @return bool|string
     */
    protected function isReadableFile(array $parameter) {
        extract($parameter);
        if(null !== parse_url($source, PHP_URL_SCHEME)) {
            return $this->isValidDomain($source);
        }
        if(is_file($source)) {
            return $source;
        } else {
            if(is_file($file = $this->config->base . '/' . $source)) {
                return $file;
            }
        }
        return false;
    }
    /**
     * @param $url
     * @return bool
     */
    protected function isValidDomain($url) {
        $trusted = $this->config->trusted_sites;
        if(!empty($trusted)) {
            extract(parse_url($url));
            $host = substr($url, 0, strpos($url, $host)) . $host;
            if(!$this->matchHost($host, $trusted)) {
                return false;
            }
        }
        return $url;
    }
    /**
     * @param $host
     * @param array $hosts
     * @return bool
     */
    protected function matchHost($host, array $hosts) {
        foreach($hosts as $trusted) {
            if(0 === strcmp($host, $trusted) || preg_match('#^' . $trusted . '#s', $host)) {
                return true;
            }
        }
        return false;
    }
}