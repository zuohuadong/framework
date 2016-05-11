<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-03-28 13:28
 */
namespace Notadd\Foundation\Agent;
use BadMethodCallException;
use Mobile_Detect;
/**
 * Class Agent
 * @package Notadd\Foundation\Agent
 */
class Agent extends Mobile_Detect {
    /**
     * @var array
     */
    protected static $additionalDevices = [
        'Macintosh' => 'Macintosh',
    ];
    /**
     * @var array
     */
    protected static $additionalOperatingSystems = [
        'Windows' => 'Windows',
        'Windows NT' => 'Windows NT',
        'OS X' => 'Mac OS X',
        'Debian' => 'Debian',
        'Ubuntu' => 'Ubuntu',
        'Macintosh' => 'PPC',
        'OpenBSD' => 'OpenBSD',
        'Linux' => 'Linux',
        'ChromeOS' => 'CrOS',
    ];
    /**
     * @var array
     */
    protected static $additionalBrowsers = [
        'Opera' => 'Opera|OPR',
        'Edge' => 'Edge',
        'Chrome' => 'Chrome',
        'Firefox' => 'Firefox',
        'Safari' => 'Safari',
        'IE' => 'MSIE|IEMobile|MSIEMobile|Trident/[.0-9]+',
        'Netscape' => 'Netscape',
        'Mozilla' => 'Mozilla',
    ];
    /**
     * @var array
     */
    protected static $additionalProperties = [
        'Windows' => 'Windows NT [VER]',
        'Windows NT' => 'Windows NT [VER]',
        'OS X' => 'OS X [VER]',
        'BlackBerryOS' => [
            'BlackBerry[\w]+/[VER]',
            'BlackBerry.*Version/[VER]',
            'Version/[VER]'
        ],
        'AndroidOS' => 'Android [VER]',
        'ChromeOS' => 'CrOS x86_64 [VER]',
        'Opera' => [
            ' OPR/[VER]',
            'Opera Mini/[VER]',
            'Version/[VER]',
            'Opera [VER]'
        ],
        'Netscape' => 'Netscape/[VER]',
        'Mozilla' => 'rv:[VER]',
        'IE' => [
            'IEMobile/[VER];',
            'IEMobile [VER]',
            'MSIE [VER];',
            'rv:[VER]'
        ],
        'Edge' => 'Edge/[VER]',
    ];
    /**
     * @var array
     */
    protected static $robots = [
        'Google' => 'googlebot',
        'MSNBot' => 'msnbot',
        'Baiduspider' => 'baiduspider',
        'Bing' => 'bingbot',
        'Yahoo' => 'yahoo',
        'Lycos' => 'lycos',
        'Facebook' => 'facebookexternalhit',
        'Twitter' => 'Twitterbot',
    ];
    /**
     * @return array
     */
    public function getDetectionRulesExtended() {
        static $rules;
        if(!$rules) {
            $rules = $this->mergeRules(static::$additionalDevices, // NEW
                static::$phoneDevices, static::$tabletDevices, static::$operatingSystems, static::$additionalOperatingSystems, // NEW
                static::$browsers, static::$additionalBrowsers, // NEW
                static::$utilities);
        }
        return $rules;
    }
    /**
     * @return array
     */
    public function getRules() {
        if($this->detectionType == static::DETECTION_TYPE_EXTENDED) {
            return static::getDetectionRulesExtended();
        } else {
            return static::getMobileDetectionRules();
        }
    }
    /**
     * @param null $acceptLanguage
     * @return array
     */
    public function languages($acceptLanguage = null) {
        if(!$acceptLanguage) {
            $acceptLanguage = $this->getHttpHeader('HTTP_ACCEPT_LANGUAGE');
        }
        if($acceptLanguage) {
            $languages = array();
            foreach(explode(',', $acceptLanguage) as $piece) {
                $parts = explode(';', $piece);
                $language = strtolower($parts[0]);
                $priority = empty($parts[1]) ? 1. : floatval(str_replace('q=', '', $parts[1]));
                $languages[$language] = $priority;
            }
            arsort($languages);
            return array_keys($languages);
        }
        return array();
    }
    /**
     * @param  array $rules
     * @param  null $userAgent
     * @return string
     */
    protected function findDetectionRulesAgainstUA(array $rules, $userAgent = null) {
        foreach($rules as $key => $regex) {
            if(empty($regex))
                continue;
            if($this->match($regex, $userAgent))
                return $key ?: reset($this->matchesArray);
        }
        return false;
    }
    /**
     * @param null $userAgent
     * @return string
     */
    public function browser($userAgent = null) {
        $rules = $this->mergeRules(static::$additionalBrowsers, static::$browsers);
        return $this->findDetectionRulesAgainstUA($rules, $userAgent);
    }
    /**
     * @param  string $userAgent
     * @return string
     */
    public function platform($userAgent = null) {
        $rules = $this->mergeRules(static::$operatingSystems, static::$additionalOperatingSystems);
        return $this->findDetectionRulesAgainstUA($rules, $userAgent);
    }
    /**
     * @param  string $userAgent
     * @return string
     */
    public function device($userAgent = null) {
        $rules = $this->mergeRules(static::$additionalDevices, static::$phoneDevices, static::$tabletDevices, static::$utilities);
        return $this->findDetectionRulesAgainstUA($rules, $userAgent);
    }
    /**
     * @param  string $userAgent deprecated
     * @param  array $httpHeaders deprecated
     * @return bool
     */
    public function isDesktop($userAgent = null, $httpHeaders = null) {
        return (!$this->isMobile() && !$this->isTablet() && !$this->isRobot());
    }
    /**
     * @param  string $userAgent deprecated
     * @param  array $httpHeaders deprecated
     * @return bool
     */
    public function isPhone($userAgent = null, $httpHeaders = null) {
        return ($this->isMobile() && !$this->isTablet());
    }
    /**
     * @param  string $userAgent
     * @return string
     */
    public function robot($userAgent = null) {
        $rules = $this->mergeRules(static::$robots, array(static::$utilities['Bot']), array(static::$utilities['MobileBot']));
        return $this->findDetectionRulesAgainstUA($rules, $userAgent);
    }
    /**
     * @param  string $userAgent
     * @return bool
     */
    public function isRobot($userAgent = null) {
        $rules = $this->mergeRules(array(static::$utilities['Bot']), array(static::$utilities['MobileBot']), static::$robots // NEW
        );
        foreach($rules as $regex) {
            if($this->match($regex, $userAgent))
                return true;
        }
        return false;
    }
    /**
     * @inherit
     * @param string $propertyName
     * @param string $type
     * @return float|string
     */
    public function version($propertyName, $type = self::VERSION_TYPE_STRING) {
        $check = key(static::$additionalProperties);
        if(!array_key_exists($check, parent::$properties)) {
            parent::$properties = array_merge(parent::$properties, static::$additionalProperties);
        }
        return parent::version($propertyName, $type);
    }
    /**
     * @return array
     */
    protected function mergeRules() {
        $merged = array();
        foreach(func_get_args() as $rules) {
            foreach($rules as $key => $value) {
                if(empty($merged[$key])) {
                    $merged[$key] = $value;
                } else {
                    if(is_array($merged[$key])) {
                        $merged[$key][] = $value;
                    } else {
                        $merged[$key] .= '|' . $value;
                    }
                }
            }
        }
        return $merged;
    }
    /**
     * @inherit
     * @param string $name
     * @param array $arguments
     * @return bool|mixed
     */
    public function __call($name, $arguments) {
        if(substr($name, 0, 2) != 'is') {
            throw new BadMethodCallException("No such method exists: $name");
        }
        $this->setDetectionType(self::DETECTION_TYPE_EXTENDED);
        $key = substr($name, 2);
        return $this->matchUAAgainstKey($key);
    }
}