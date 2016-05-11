<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2015-10-18 16:28
 */
use Illuminate\Support\Str;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Access\Gate;
use Notadd\Foundation\SearchEngine\Optimization;
if(!function_exists('abort')) {
    /**
     * @param int $code
     * @param string $message
     * @param array $headers
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort($code, $message = '', array $headers = []) {
        return app()->abort($code, $message, $headers);
    }
}
if(!function_exists('action')) {
    /**
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @return string
     */
    function action($name, $parameters = [], $absolute = true) {
        return app('url')->action($name, $parameters, $absolute);
    }
}
if(!function_exists('app')) {
    /**
     * @param string $make
     * @param array $parameters
     * @return mixed|\Notadd\Foundation\Application
     */
    function app($make = null, $parameters = []) {
        if(is_null($make)) {
            return Container::getInstance();
        }
        return Container::getInstance()->make($make, $parameters);
    }
}
if(!function_exists('app_path')) {
    /**
     * @param string $path
     * @return string
     */
    function app_path($path = '') {
        return app('path') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if(!function_exists('asset')) {
    /**
     * @param string $path
     * @param bool $secure
     * @return string
     */
    function asset($path, $secure = null) {
        return app('url')->asset($path, $secure);
    }
}
if(!function_exists('auth')) {
    /**
     * @return \Illuminate\Contracts\Auth\Guard
     */
    function auth() {
        return app('Illuminate\Contracts\Auth\Guard');
    }
}
if(!function_exists('base_path')) {
    /**
     * @param string $path
     * @return string
     */
    function base_path($path = '') {
        return app()->basePath() . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if(!function_exists('back')) {
    /**
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Http\RedirectResponse
     */
    function back($status = 302, $headers = []) {
        return app('redirect')->back($status, $headers);
    }
}
if(!function_exists('bcrypt')) {
    /**
     * @param string $value
     * @param array $options
     * @return string
     */
    function bcrypt($value, $options = []) {
        return app('hash')->make($value, $options);
    }
}
if(!function_exists('config')) {
    /**
     * @param array|string $key
     * @param mixed $default
     * @return mixed
     */
    function config($key = null, $default = null) {
        if(is_null($key)) {
            return app('config');
        }
        if(is_array($key)) {
            return app('config')->set($key);
        }
        return app('config')->get($key, $default);
    }
}
if(!function_exists('config_path')) {
    /**
     * @param string $path
     * @return string
     */
    function config_path($path = '') {
        return app()->make('path.config') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if(!function_exists('cookie')) {
    /**
     * @param string $name
     * @param string $value
     * @param int $minutes
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return \Symfony\Component\HttpFoundation\Cookie
     */
    function cookie($name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = false, $httpOnly = true) {
        $cookie = app('Illuminate\Contracts\Cookie\Factory');
        if(is_null($name)) {
            return $cookie;
        }
        return $cookie->make($name, $value, $minutes, $path, $domain, $secure, $httpOnly);
    }
}
if(!function_exists('csrf_field')) {
    /**
     * @return string
     */
    function csrf_field() {
        return new Illuminate\View\Expression('<input type="hidden" name="_token" value="' . csrf_token() . '">');
    }
}
if(!function_exists('csrf_token')) {
    /**
     * @return string
     * @throws RuntimeException
     */
    function csrf_token() {
        $session = app('session');
        if(isset($session)) {
            return $session->getToken();
        }
        throw new RuntimeException('Application session store not set.');
    }
}
if(!function_exists('extension_path')) {
    function extension_path($path) {
        return base_path('extensions') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if(!function_exists('delete')) {
    /**
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Illuminate\Routing\Route
     */
    function delete($uri, $action) {
        return app('router')->delete($uri, $action);
    }
}
if(!function_exists('factory')) {
    /**
     * @param dynamic  class|class,name|class,amount|class,name,amount
     * @return \Illuminate\Database\Eloquent\FactoryBuilder
     */
    function factory() {
        $factory = app('Illuminate\Database\Eloquent\Factory');
        $arguments = func_get_args();
        if(isset($arguments[1]) && is_string($arguments[1])) {
            return $factory->of($arguments[0], $arguments[1])->times(isset($arguments[2]) ? $arguments[2] : 1);
        } elseif(isset($arguments[1])) {
            return $factory->of($arguments[0])->times($arguments[1]);
        } else {
            return $factory->of($arguments[0]);
        }
    }
}
if(!function_exists('framework_path')) {
    /**
     * @param $path
     * @return string
     */
    function framework_path($path) {
        return app()->make('path.framework') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if(!function_exists('get')) {
    /**
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Illuminate\Routing\Route
     */
    function get($uri, $action) {
        return app('router')->get($uri, $action);
    }
}
if(!function_exists('info')) {
    /**
     * @param string $message
     * @param array $context
     * @return void
     */
    function info($message, $context = []) {
        return app('log')->info($message, $context);
    }
}
if(!function_exists('logger')) {
    /**
     * @param string $message
     * @param array $context
     * @return null|\Illuminate\Contracts\Logging\Log
     */
    function logger($message = null, array $context = []) {
        if(is_null($message)) {
            return app('log');
        }
        return app('log')->debug($message, $context);
    }
}
if(!function_exists('method_field')) {
    /**
     * @param string $method
     * @return string
     */
    function method_field($method) {
        return new Illuminate\View\Expression('<input type="hidden" name="_method" value="' . $method . '">');
    }
}
if(!function_exists('old')) {
    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function old($key = null, $default = null) {
        return app('request')->old($key, $default);
    }
}
if(!function_exists('patch')) {
    /**
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Illuminate\Routing\Route
     */
    function patch($uri, $action) {
        return app('router')->patch($uri, $action);
    }
}
if(!function_exists('policy')) {
    /**
     * @param object|string $class
     * @return mixed
     * @throws \InvalidArgumentException
     */
    function policy($class) {
        return app(Gate::class)->getPolicyFor($class);
    }
}
if(!function_exists('post')) {
    /**
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Illuminate\Routing\Route
     */
    function post($uri, $action) {
        return app('router')->post($uri, $action);
    }
}
if(!function_exists('put')) {
    /**
     * @param string $uri
     * @param \Closure|array|string $action
     * @return \Illuminate\Routing\Route
     */
    function put($uri, $action) {
        return app('router')->put($uri, $action);
    }
}
if(!function_exists('public_path')) {
    /**
     * @param string $path
     * @return string
     */
    function public_path($path = '') {
        return app()->make('path.public') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if(!function_exists('seo')) {
    /**
     * @param string $meta
     * @return string
     */
    function seo($meta) {
        return app()->make(Optimization::class)->getData($meta);
    }
}
if(!function_exists('setting')) {
    /**
     * @param string $key
     * @param string $default
     * @return string
     */
    function setting($key, $default = '') {
        return app('setting')->get($key, $default);
    }
}
if(!function_exists('stubs_path')) {
    /**
     * @param $path
     * @return string
     */
    function stubs_path($path) {
        return app()->make('path.stubs') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if(!function_exists('redirect')) {
    /**
     * @param string|null $to
     * @param int $status
     * @param array $headers
     * @param bool $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null) {
        if(is_null($to)) {
            return app('redirect');
        }
        return app('redirect')->to($to, $status, $headers, $secure);
    }
}
if(!function_exists('request')) {
    /**
     * @param string $key
     * @param mixed $default
     * @return \Illuminate\Http\Request|string|array
     */
    function request($key = null, $default = null) {
        if(is_null($key)) {
            return app('request');
        }
        return app('request')->input($key, $default);
    }
}
if(!function_exists('resource')) {
    /**
     * @param string $name
     * @param string $controller
     * @param array $options
     * @return void
     */
    function resource($name, $controller, array $options = []) {
        return app('router')->resource($name, $controller, $options);
    }
}
if(!function_exists('response')) {
    /**
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function response($content = '', $status = 200, array $headers = []) {
        $factory = app('Illuminate\Contracts\Routing\ResponseFactory');
        if(func_num_args() === 0) {
            return $factory;
        }
        return $factory->make($content, $status, $headers);
    }
}
if(!function_exists('route')) {
    /**
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @param \Illuminate\Routing\Route $route
     * @return string
     */
    function route($name, $parameters = [], $absolute = true, $route = null) {
        return app('url')->route($name, $parameters, $absolute, $route);
    }
}
if(!function_exists('secure_asset')) {
    /**
     * @param string $path
     * @return string
     */
    function secure_asset($path) {
        return asset($path, true);
    }
}
if(!function_exists('secure_url')) {
    /**
     * @param string $path
     * @param mixed $parameters
     * @return string
     */
    function secure_url($path, $parameters = []) {
        return url($path, $parameters, true);
    }
}
if(!function_exists('session')) {
    /**
     * @param array|string $key
     * @param mixed $default
     * @return mixed
     */
    function session($key = null, $default = null) {
        if(is_null($key)) {
            return app('session');
        }
        if(is_array($key)) {
            return app('session')->put($key);
        }
        return app('session')->get($key, $default);
    }
}
if(!function_exists('storage_path')) {
    /**
     * @param string $path
     * @return string
     */
    function storage_path($path = '') {
        return app('path.storage') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}
if(!function_exists('trans')) {
    /**
     * @param string $id
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     * @return string
     */
    function trans($id = null, $parameters = [], $domain = 'messages', $locale = null) {
        if(is_null($id)) {
            return app('translator');
        }
        return app('translator')->trans($id, $parameters, $domain, $locale);
    }
}
if(!function_exists('trans_choice')) {
    /**
     * @param string $id
     * @param int $number
     * @param array $parameters
     * @param string $domain
     * @param string $locale
     * @return string
     */
    function trans_choice($id, $number, array $parameters = [], $domain = 'messages', $locale = null) {
        return app('translator')->transChoice($id, $number, $parameters, $domain, $locale);
    }
}
if(!function_exists('url')) {
    /**
     * @param string $path
     * @param mixed $parameters
     * @param bool $secure
     * @return string
     */
    function url($path = null, $parameters = [], $secure = null) {
        return app('Illuminate\Contracts\Routing\UrlGenerator')->to($path, $parameters, $secure);
    }
}
if(!function_exists('view')) {
    /**
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return \Illuminate\View\View
     */
    function view($view = null, $data = [], $mergeData = []) {
        $factory = app('Illuminate\Contracts\View\Factory');
        if(func_num_args() === 0) {
            return $factory;
        }
        return $factory->make($view, $data, $mergeData);
    }
}
if(!function_exists('env')) {
    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env($key, $default = null) {
        $value = getenv($key);
        if($value === false) {
            return value($default);
        }
        switch(strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return;
        }
        if(Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            return substr($value, 1, -1);
        }
        return $value;
    }
}
if(!function_exists('event')) {
    /**
     * @param string|object $event
     * @param mixed $payload
     * @param bool $halt
     * @return array|null
     */
    function event($event, $payload = [], $halt = false) {
        return app('events')->fire($event, $payload, $halt);
    }
}
if(!function_exists('elixir')) {
    /**
     * @param string $file
     * @return string
     * @throws \InvalidArgumentException
     */
    function elixir($file) {
        static $manifest = null;
        if(is_null($manifest)) {
            $manifest = json_decode(file_get_contents(public_path('build/rev-manifest.json')), true);
        }
        if(isset($manifest[$file])) {
            return '/build/' . $manifest[$file];
        }
        throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
    }
}