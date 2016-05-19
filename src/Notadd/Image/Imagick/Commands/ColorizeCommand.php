<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-19 18:50
 */
namespace Notadd\Image\Imagick\Commands;
use Notadd\Image\Commands\AbstractCommand;
/**
 * Class ColorizeCommand
 * @package Notadd\Image\Imagick\Commands
 */
class ColorizeCommand extends AbstractCommand {
    /**
     * @param  \Notadd\Image\Image $image
     * @return boolean
     */
    public function execute($image) {
        $red = $this->argument(0)->between(-100, 100)->required()->value();
        $green = $this->argument(1)->between(-100, 100)->required()->value();
        $blue = $this->argument(2)->between(-100, 100)->required()->value();
        $red = $this->normalizeLevel($red);
        $green = $this->normalizeLevel($green);
        $blue = $this->normalizeLevel($blue);
        $qrange = $image->getCore()->getQuantumRange();
        $image->getCore()->levelImage(0, $red, $qrange['quantumRangeLong'], \Imagick::CHANNEL_RED);
        $image->getCore()->levelImage(0, $green, $qrange['quantumRangeLong'], \Imagick::CHANNEL_GREEN);
        $image->getCore()->levelImage(0, $blue, $qrange['quantumRangeLong'], \Imagick::CHANNEL_BLUE);
        return true;
    }
    /**
     * @param $level
     * @return float
     */
    private function normalizeLevel($level) {
        if($level > 0) {
            return $level / 5;
        } else {
            return ($level + 100) / 100;
        }
    }
}