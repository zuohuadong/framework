<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-08-31 20:21
 */
namespace Notadd\Foundation\Database\Contracts;
/**
 * Interface MigrationRepository
 * @package Notadd\Foundation\Database\Contracts
 */
interface MigrationRepository {
    /**
     * @param null $extension
     * @return array
     */
    public function getRan($extension = null);
    /**
     * @param string $file
     * @param string $extension
     * @return void
     */
    public function log($file, $extension = null);
    /**
     * @param string $file
     * @param string $extension
     * @return void
     */
    public function delete($file, $extension = null);
    /**
     * @return void
     */
    public function createRepository();
    /**
     * @return bool
     */
    public function repositoryExists();
    /**
     * @param  string $name
     * @return void
     */
    public function setSource($name);
}