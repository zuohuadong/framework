<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:49
 */
namespace Notadd\Foundation\Passport\Bridges\Repositories;
use Notadd\Foundation\Passport\Bridges\Client;
use Notadd\Foundation\Passport\Repositories\ClientRepository as ClientModelRepository;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
/**
 * Class ClientRepository
 * @package Notadd\Foundation\Passport\Bridges
 */
class ClientRepository implements ClientRepositoryInterface {
    /**
     * @var \Notadd\Foundation\Passport\Repositories\ClientRepository
     */
    protected $clients;
    /**
     * ClientRepository constructor.
     * @param \Notadd\Foundation\Passport\Repositories\ClientRepository $clients
     */
    public function __construct(ClientModelRepository $clients) {
        $this->clients = $clients;
    }
    /**
     * @param string $clientIdentifier
     * @param string $grantType
     * @param null $clientSecret
     * @param bool $mustValidateSecret
     * @return \Notadd\Foundation\Passport\Bridges\Client|void
     */
    public function getClientEntity($clientIdentifier, $grantType, $clientSecret = null, $mustValidateSecret = true) {
        $record = $this->clients->findActive($clientIdentifier);
        if(!$record || !$this->handlesGrant($record, $grantType)) {
            return;
        }
        $client = new Client($clientIdentifier, $record->name, $record->redirect);
        if($mustValidateSecret && !hash_equals($record->secret, (string)$clientSecret)) {
            return;
        }
        return $client;
    }
    /**
     * @param $record
     * @param $grantType
     * @return bool
     */
    protected function handlesGrant($record, $grantType) {
        switch($grantType) {
            case 'authorization_code':
                return !$record->firstParty();
            case 'personal_access':
                return $record->personal_access_client;
            case 'password':
                return $record->password_client;
            default:
                return true;
        }
    }
}