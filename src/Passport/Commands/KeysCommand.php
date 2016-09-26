<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-26 17:59
 */
namespace Notadd\Passport\Commands;
use Notadd\Foundation\Console\Abstracts\AbstractCommand;
use Notadd\Foundation\Passport\Passport;
use phpseclib\Crypt\RSA;
/**
 * Class KeysCommand
 * @package Notadd\Passport\Commands
 */
class KeysCommand extends AbstractCommand {
    /**
     * @var \phpseclib\Crypt\RSA
     */
    protected $rsa;
    /**
     * KeysCommand constructor.
     * @param \phpseclib\Crypt\RSA $rsa
     */
    public function __construct(RSA $rsa) {
        parent::__construct();
        $this->rsa = $rsa;
    }
    /**
     * @return void
     */
    public function configure() {
        $this->setDescription('Create the encryption keys for API authentication');
        $this->setName('passport:keys');
    }
    /**
     * @return void
     */
    public function fire() {
        $keys = $this->rsa->createKey(4096);
        file_put_contents(Passport::keyPath('oauth-private.key'), array_get($keys, 'privatekey'));
        file_put_contents(Passport::keyPath('oauth-public.key'), array_get($keys, 'publickey'));
        $this->info('Encryption keys generated successfully.');
    }
}