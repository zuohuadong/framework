<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-02 19:17
 */
namespace Notadd\Foundation\Console\Abstracts;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
/**
 * Class AbstractCommand
 * @package Notadd\Foundation\Console\Abstracts
 */
abstract class AbstractCommand extends Command {
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;
    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;
    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->input = $input;
        $this->output = $output;
        return $this->fire();
    }
    /**
     * @return mixed
     */
    abstract protected function fire();
    /**
     * @param $name
     * @return bool
     */
    protected function hasOption($name) {
        return $this->input->hasOption($name);
    }
    /**
     * @param $string
     */
    protected function info($string) {
        $this->output->writeln("<info>$string</info>");
    }
}