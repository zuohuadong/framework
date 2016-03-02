<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-02-26 20:39
 */
namespace Notadd\Theme\Minifiers;
use Notadd\Theme\Contracts\Minifier;
class JavaScript implements Minifier {
    /**
     * @var string
     */
    protected $input;
    /**
     * @var int
     */
    protected $index = 0;
    /**
     * @var string
     */
    protected $a = '';
    /**
     * @var string
     */
    protected $b = '';
    /**
     * @var string
     */
    protected $c;
    /**
     * @var array
     */
    protected $options;
    /**
     * @var array
     */
    protected static $defaultOptions = array('flaggedComments' => true);
    /**
     * @var array
     */
    protected $locks = array();
    /**
     * @param  string $js
     * @param  array $options
     * @throws \Exception
     * @return bool|string
     */
    public static function execute($js, $options = array()) {
        try {
            ob_start();
            $jshrink = new static();
            $js = $jshrink->lock($js);
            $jshrink->minifyDirectToOutput($js, $options);
            $js = ltrim(ob_get_clean());
            $js = $jshrink->unlock($js);
            unset($jshrink);
            return $js;
        } catch(\Exception $e) {
            if(isset($jshrink)) {
                $jshrink->clean();
                unset($jshrink);
            }
            ob_end_clean();
            throw $e;
        }
    }
    /**
     * @param string $js
     * @param array $options
     */
    protected function minifyDirectToOutput($js, $options) {
        $this->initialize($js, $options);
        $this->loop();
        $this->clean();
    }
    /**
     * @param string $js
     * @param array $options
     */
    protected function initialize($js, $options) {
        $this->options = array_merge(static::$defaultOptions, $options);
        $js = str_replace("\r\n", "\n", $js);
        $js = str_replace('/**/', '', $js);
        $this->input = str_replace("\r", "\n", $js);
        $this->input .= PHP_EOL;
        $this->a = "\n";
        $this->b = $this->getReal();
    }
    /**
     * @return  void
     */
    protected function loop() {
        while($this->a !== false && !is_null($this->a) && $this->a !== '') {
            switch($this->a) {
                case "\n":
                    if(strpos('(-+{[@', $this->b) !== false) {
                        echo $this->a;
                        $this->saveString();
                        break;
                    }
                    if($this->b === ' ')
                        break;
                case ' ':
                    if(static::isAlphaNumeric($this->b))
                        echo $this->a;
                    $this->saveString();
                    break;
                default:
                    switch($this->b) {
                        case "\n":
                            if(strpos('}])+-"\'', $this->a) !== false) {
                                echo $this->a;
                                $this->saveString();
                                break;
                            } else {
                                if(static::isAlphaNumeric($this->a)) {
                                    echo $this->a;
                                    $this->saveString();
                                }
                            }
                            break;
                        case ' ':
                            if(!static::isAlphaNumeric($this->a))
                                break;
                        default:
                            if($this->a === '/' && ($this->b === '\'' || $this->b === '"')) {
                                $this->saveRegex();
                                continue;
                            }
                            echo $this->a;
                            $this->saveString();
                            break;
                    }
            }
            $this->b = $this->getReal();
            if(($this->b == '/' && strpos('(,=:[!&|?', $this->a) !== false))
                $this->saveRegex();
        }
    }
    /**
     * @return  void
     */
    protected function clean() {
        unset($this->input);
        $this->index = 0;
        $this->a = $this->b = '';
        unset($this->c);
        unset($this->options);
    }
    /**
     * @return string
     */
    protected function getChar() {
        if(isset($this->c)) {
            $char = $this->c;
            unset($this->c);
        } else {
            $char = substr($this->input, $this->index, 1);
            if(isset($char) && $char === false) {
                return false;
            }
            $this->index++;
        }
        if($char !== "\n" && ord($char) < 32)
            return ' ';
        return $char;
    }
    /**
     * @return string
     * @throws \RuntimeException
     */
    protected function getReal() {
        $startIndex = $this->index;
        $char = $this->getChar();
        if($char !== '/') {
            return $char;
        }
        $this->c = $this->getChar();
        if($this->c === '/') {
            return $this->processOneLineComments($startIndex);
        } elseif($this->c === '*') {
            return $this->processMultiLineComments($startIndex);
        }
        return $char;
    }
    /**
     * @param  int $startIndex
     * @return string
     */
    protected function processOneLineComments($startIndex) {
        $thirdCommentString = substr($this->input, $this->index, 1);
        $this->getNext("\n");
        if($thirdCommentString == '@') {
            $endPoint = $this->index - $startIndex;
            unset($this->c);
            $char = "\n" . substr($this->input, $startIndex, $endPoint);
        } else {
            $this->getChar();
            $char = $this->getChar();
        }
        return $char;
    }
    /**
     * @param  int $startIndex
     * @return bool|string
     * @throws \RuntimeException
     */
    protected function processMultiLineComments($startIndex) {
        $this->getChar();
        $thirdCommentString = $this->getChar();
        if($this->getNext('*/')) {
            $this->getChar();
            $this->getChar();
            $char = $this->getChar();
            if(($this->options['flaggedComments'] && $thirdCommentString === '!') || ($thirdCommentString === '@')) {
                if($startIndex > 0) {
                    echo $this->a;
                    $this->a = " ";
                    if($this->input[($startIndex - 1)] === "\n") {
                        echo "\n";
                    }
                }
                $endPoint = ($this->index - 1) - $startIndex;
                echo substr($this->input, $startIndex, $endPoint);
                return $char;
            }
        } else {
            $char = false;
        }
        if($char === false)
            throw new \RuntimeException('Unclosed multiline comment at position: ' . ($this->index - 2));
        if(isset($this->c))
            unset($this->c);
        return $char;
    }
    /**
     * @param  string $string
     * @return string|false
     */
    protected function getNext($string) {
        $pos = strpos($this->input, $string, $this->index);
        if($pos === false)
            return false;
        $this->index = $pos;
        return substr($this->input, $this->index, 1);
    }
    /**
     * @throws \RuntimeException
     */
    protected function saveString() {
        $startpos = $this->index;
        $this->a = $this->b;
        if($this->a !== "'" && $this->a !== '"') {
            return;
        }
        $stringType = $this->a;
        echo $this->a;
        while(true) {
            $this->a = $this->getChar();
            switch($this->a) {
                case $stringType:
                    break 2;
                case "\n":
                    throw new \RuntimeException('Unclosed string at position: ' . $startpos);
                    break;
                case '\\':
                    $this->b = $this->getChar();
                    if($this->b === "\n") {
                        break;
                    }
                    echo $this->a . $this->b;
                    break;
                default:
                    echo $this->a;
            }
        }
    }
    /**
     * @throws \RuntimeException
     */
    protected function saveRegex() {
        echo $this->a . $this->b;
        while(($this->a = $this->getChar()) !== false) {
            if($this->a === '/')
                break;
            if($this->a === '\\') {
                echo $this->a;
                $this->a = $this->getChar();
            }
            if($this->a === "\n")
                throw new \RuntimeException('Unclosed regex pattern at position: ' . $this->index);
            echo $this->a;
        }
        $this->b = $this->getReal();
    }
    /**
     * @param  string $char
     * @return bool
     */
    protected static function isAlphaNumeric($char) {
        return preg_match('/^[\w\$\pL]$/', $char) === 1 || $char == '/';
    }
    /**
     * @param  string $js
     * @return bool
     */
    protected function lock($js) {
        $lock = '"LOCK---' . crc32(time()) . '"';
        $matches = array();
        preg_match('/([+-])(\s+)([+-])/S', $js, $matches);
        if(empty($matches)) {
            return $js;
        }
        $this->locks[$lock] = $matches[2];
        $js = preg_replace('/([+-])\s+([+-])/S', "$1{$lock}$2", $js);
        return $js;
    }
    /**
     * @param  string $js
     * @return bool
     */
    protected function unlock($js) {
        if(empty($this->locks)) {
            return $js;
        }
        foreach($this->locks as $lock => $replacement) {
            $js = str_replace($lock, $replacement, $js);
        }
        return $js;
    }
}