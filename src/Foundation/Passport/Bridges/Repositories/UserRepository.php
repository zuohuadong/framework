<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:56
 */
namespace Notadd\Foundation\Passport\Bridges\Repositories;
use Illuminate\Contracts\Hashing\Hasher;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Notadd\Foundation\Passport\Bridges\User;
use RuntimeException;
/**
 * Class UserRepository
 * @package Notadd\Foundation\Passport\Bridges
 */
class UserRepository implements UserRepositoryInterface {
    /**
     * @var \Illuminate\Contracts\Hashing\Hasher
     */
    protected $hasher;
    /**
     * UserRepository constructor.
     * @param \Illuminate\Contracts\Hashing\Hasher $hasher
     */
    public function __construct(Hasher $hasher) {
        $this->hasher = $hasher;
    }
    /**
     * @param string $username
     * @param string $password
     * @param string $grantType
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $clientEntity
     * @return \Notadd\Foundation\Passport\Bridges\User|void
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity) {
        if(is_null($model = config('auth.providers.users.model'))) {
            throw new RuntimeException('Unable to determine user model from configuration.');
        }
        if(method_exists($model, 'findForPassport')) {
            $user = (new $model)->findForPassport($username);
        } else {
            $user = (new $model)->where('email', $username)->first();
        }
        if(!$user || !$this->hasher->check($password, $user->password)) {
            return;
        }
        return new User($user->getAuthIdentifier());
    }
}