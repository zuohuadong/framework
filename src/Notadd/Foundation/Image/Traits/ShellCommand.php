<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2015, iBenchu.org
 * @datetime 2016-05-14 12:42
 */
namespace Notadd\Foundation\Image\Traits;
use Closure;
/**
 * Class ShellCommand
 * @package Notadd\Foundation\Image\Traits
 */
trait ShellCommand {
    /**
     * @var array
     */
    private $cmds = [];
    /**
     * @param string $cmd
     * @param string $exception
     * @param Closure $callback
     * @param array $noEscapeChars
     * @return string
     */
    public function runCmd($cmd, $exception = '\RuntimeException', Closure $callback = null, array $noEscapeChars = null) {
        $cmd = escapeshellcmd($cmd);
        if(is_array($noEscapeChars) and !empty($noEscapeChars)) {
            $repl = "\\\\" . implode("|\\\\", $noEscapeChars);
            $cmd = preg_replace_callback("~$repl~", function ($found) {
                return trim($found[0], "\\");
            }, $cmd);
        }
        $this->cmds[] = $cmd;
        $exitStatus = $this->execCmd($cmd, $stdout, $stderr);
        if($exitStatus > 0) {
            if(!is_null($callback)) {
                $callback($stderr);
            }
            throw new $exception(sprintf('Command exited with %d: %s', $exitStatus, $stderr));
        }
        return $stdout;
    }
    /**
     * @return string
     */
    public function getLastCmd() {
        $cmds = $this->cmds;
        return array_pop($cmds);
    }
    /**
     * @param string $cmd
     * @param string $stdout
     * @param string $stderr
     * @return mixed
     */
    private function execCmd($cmd, &$stdout = null, &$stderr = null) {
        $descriptorspec = array(
            0 => array(
                "pipe",
                "r"
            ),
            1 => array(
                "pipe",
                "w"
            ),
            2 => array(
                "pipe",
                "w"
            )
        );
        $pipes = array();
        $process = proc_open($cmd, $descriptorspec, $pipes);
        $stdout = "";
        $stderr = "";
        if(!is_resource($process)) {
            return false;
        }
        fclose($pipes[0]);
        stream_set_blocking($pipes[1], false);
        stream_set_blocking($pipes[2], false);
        $todo = array(
            $pipes[1],
            $pipes[2]
        );
        while(true) {
            $readstdout = [];
            $readstderr = [];
            if(false !== !feof($pipes[1])) {
                $readstdout[] = $pipes[1];
            }
            if(false !== !feof($pipes[2])) {
                $readstderr[] = $pipes[2];
            }
            if(empty($readstdout)) {
                break;
            }
            $write = null;
            $ex = null;
            $ready = stream_select($readstdout, $write, $ex, 2);
            if(false === $ready) {
                break;
            }
            foreach($readstdout as $out) {
                $line = fread($out, 1024);
                $stdout .= $line;
            }
            foreach($readstderr as $out) {
                $line = fread($out, 1024);
                $stderr .= $line;
            }
        }
        $stdout = strlen($stdout) > 0 ? $stdout : null;
        $stderr = strlen($stderr) > 0 ? $stderr : null;
        fclose($pipes[1]);
        fclose($pipes[2]);
        return proc_close($process);
    }
}