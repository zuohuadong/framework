<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 12:34
 */
namespace Notadd\Foundation\Image\Drivers;
use Notadd\Foundation\Image\Contracts\BinLocator;
use Notadd\Foundation\Image\Contracts\SourceLoader;
use Notadd\Foundation\Image\Traits\Scaling;
use Notadd\Foundation\Image\Traits\ShellCommand;
/**
 * Class ImDriver
 * @package Notadd\Foundation\Image\Drivers
 */
class ImDriver extends AbstractDriver {
    use Scaling;
    use ShellCommand;
    /**
     * @var string
     */
    protected static $driverType = 'im';
    /**
     * @var string
     */
    protected $source;
    /**
     * @var mixed
     */
    protected $loader;
    /**
     * @var array
     */
    protected $commands = [];
    /**
     * @var mixed
     */
    protected $targetSize = [];
    /**
     * @var string
     */
    protected $tmp;
    /**
     * @var string
     */
    protected $tmpFile;
    /**
     * @var mixed
     */
    protected $intermediate;
    /**
     * @var int
     */
    protected $imageFrames;
    /**
     * @var string
     */
    private $converter;
    /**
     * ImDriver constructor.
     * @param \Notadd\Foundation\Image\Contracts\BinLocator $locator
     * @param \Notadd\Foundation\Image\Contracts\SourceLoader $loader
     */
    public function __construct(BinLocator $locator, SourceLoader $loader) {
        $this->tmp = sys_get_temp_dir();
        $this->loader = $loader;
        $this->converter = $locator->getConverterPath();
    }
    /**
     * @param string $source
     * @return bool
     */
    public function load($source) {
        $this->clean();
        if($src = $this->loader->load($source)) {
            $this->source = $src;
            return true;
        }
        $this->error = 'error loading source';
        return false;
    }
    /**
     * @throws Thapp\JitImage\Exception\ImageProcessException;
     */
    public function process() {
        parent::process();
        $cmd = $this->compile();
        $this->runCmd($cmd, '\Thapp\JitImage\Exception\ImageProcessException', function ($stderr) {
            $this->clean();
        }, [
                '#',
                PHP_EOL
            ]);
    }
    /**
     * @return void
     */
    public function clean() {
        if(file_exists($this->tmpFile)) {
            @unlink($this->tmpFile);
        }
        if(file_exists($this->intermediate)) {
            @unlink($this->intermediate);
        }
        $this->commands = [];
        $this->loader->clean();
        parent::clean();
    }
    /**
     * @param mixed $name
     * @param mixed $options
     * @return boolean|void
     */
    public function filter($name, array $options = []) {
        if(static::EXT_FILTER === parent::filter($name, $options) and isset($this->filters[$name])) {
            $filter = new $this->filters[$name]($this, $options);
            $filterResults = $filter->run();
            if(!empty($filterResults)) {
                $this->commands = array_merge($this->commands, (array)$filterResults);
            }
        }
    }
    /**
     * @access public
     * @return mixed
     */
    public function getResource() {
        return null;
    }
    /**
     * @access public
     * @return mixed
     */
    public function swapResource($resource) {
        return null;
    }
    /**
     * @param mixed $quality
     * @access public
     * @return mixed
     */
    public function setQuality($quality) {
        $this->commands['-quality %d'] = [(int)$quality];
    }
    /**
     * @return string
     */
    public function getImageBlob() {
        return file_get_contents($this->tmpFile ?: $this->source);
    }
    /**
     * @param string $color
     * @return $this
     */
    protected function background($color = null) {
        if(is_string($color)) {
            $this->commands['-background "#%s"'] = [trim($color, '#')];
        }
        return $this;
    }
    /**
     * @return $this
     */
    protected function resize($width, $height, $flag = '') {
        $min = min($width, $height);
        $cmd = '-resize %sx%s%s';
        switch($flag) {
            case static::FL_OSRK_LGR:
                break;
            case static::FL_RESZ_PERC:
                $cmd = '-resize %s%s%s';
                break;
            case static::FL_IGNR_ASPR:
            default:
                // compensating some imagick /im differences:
                if(0 === $width) {
                    $width = (int)floor($height * $this->getInfo('ratio'));
                }
                if(0 === $height) {
                    $height = (int)floor($width / $this->getInfo('ratio'));
                }
                $h = '';
                break;
        }
        $w = $this->getValueString($width);
        $h = $this->getValueString($height);
        $this->commands[$cmd] = [
            $w,
            $h,
            $flag
        ];
        return $this;
    }
    /**
     * @param mixed $w
     * @param mixed $h
     * @return mixed
     */
    private function getMinTargetSize($w, $h) {
        extract($this->getInfo());
        if($w > $width or $h > $height) {
            $w = $width;
            $h = $height;
        }
        if($w > $h) {
            extract($this->getFilesize($w, 0));
        } else {
            extract($this->getFilesize(0, $h));
        }
        $this->targetSize = compact('width', 'height');
    }
    /**
     * @param mixed $width
     * @param mixed $height
     * @param string $flag
     * @return $this
     */
    protected function extent($width, $height, $flag = '') {
        $this->commands['-extent %sx%s%s'] = [
            (string)$width,
            (string)$height,
            $flag
        ];
        return $this;
    }
    /**
     * @param mixed $gravity
     * @param string $flag
     * @return $this
     */
    protected function gravity($gravity, $flag = '') {
        $this->commands['-gravity %s%s'] = [
            $this->getGravityValue($gravity),
            $flag
        ];
        return $this;
    }
    /**
     * @param mixed $width
     * @param mixed $height
     * @param string $flag
     * @return $this
     */
    protected function scale($width, $height, $flag = '') {
        $this->commands['-scale %s%s%s'] = [
            $width,
            $height,
            $flag = ''
        ];
        return $this;
    }
    /**
     * @return $this
     */
    protected function repage() {
        $this->commands['%srepage'] = ['+'];
        return $this;
    }
    /**
     * @param null $extenstion
     * @return string
     */
    protected function getTempFile($extenstion = null) {
        $extenstion = is_null($extenstion) ? '' : '.' . $extenstion;
        return tempnam($this->tmp, 'jitim_' . $extenstion);
    }
    /**
     * @return mixed
     */
    protected function isMultipartImage() {
        if(!is_int($this->imageFrames)) {
            $type = $this->getInfo('type');
            if('image/gif' !== $type and 'image/png' !== $type) {
                $this->imageFrames = 1;
            } else {
                $identify = dirname($this->converter) . '/identify';
                $cmd = sprintf('%s -format %s %s', $identify, '%n', $this->source);
                $this->imageFrames = (int)$this->runCmd($cmd, '\Thapp\JitImage\Exception\ImageProcessException', function ($stderr) {
                    $this->clean();
                }, ['#']);
            }
        }
        return $this->imageFrames > 1;
    }
    /**
     * @access protected
     * @return string
     */
    private function compile() {
        $commands = array_keys($this->commands);
        $values = $this->getArrayValues(array_values($this->commands));
        $origSource = $this->source;
        $vs = '%s';
        $bin = $this->converter;
        $type = preg_replace('#^image/#', null, $this->getInfo('type'));
        $this->tmpFile = $this->getTempFile();
        if($this->isMultipartImage()) {
            $this->intermediate = $this->getTempFile($type);
            $this->source = $this->intermediate;
        }
        array_unshift($values, sprintf('%s:%s', $type, escapeshellarg($this->source)));
        array_unshift($values, $bin);
        array_unshift($commands, $vs);
        array_unshift($commands, $vs);
        if($this->isMultipartImage()) {
            array_unshift($values, sprintf('%s %s:%s -coalesce %s %s', $this->converter, $type, $origSource, $this->intermediate, PHP_EOL));
            array_unshift($commands, $vs);
        }
        array_push($values, sprintf('%s:%s', $this->getOutputType(), $this->tmpFile));
        array_push($commands, $vs);
        $cmd = implode(' ', $commands);
        $this->source = $origSource;
        return vsprintf($cmd, $values);
    }
    /**
     * @param mixed $array
     * @return mixed
     */
    private function getArrayValues($array) {
        $out = [];
        $it = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));
        foreach($it as $value) {
            $out[] = $value;
        }
        return $out;
    }
    /**
     * @param mixed $gravity
     * @return string
     */
    protected function getGravityValue($gravity) {
        switch($gravity) {
            case 1:
                return 'northwest';
            case 2:
                return 'north';
            case 3:
                return 'northeast';
            case 4:
                return 'west';
            case 5:
                return 'center';
            case 6:
                return 'east';
            case 7:
                return 'southwest';
            case 8:
                return 'south';
            case 9:
                return 'southeast';
            default:
                return 'center';
        }
    }
    /**
     * @param mixed $value
     * @return string
     */
    private function getValueString($value) {
        return (string)(0 === $value ? '' : $value);
    }
}