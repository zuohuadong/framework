<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:25
 */
namespace Notadd\Foundation\Passport\Repositories;
use Notadd\Foundation\Passport\Client;
use Notadd\Foundation\Passport\Passport;
use Notadd\Foundation\Passport\PersonalAccessClient;
/**
 * Class ClientRepository
 * @package Notadd\Foundation\Passport
 */
class ClientRepository {
    /**
     * @param $id
     * @return \Notadd\Foundation\Passport\Client
     */
    public function find($id) {
        return Client::find($id);
    }
    /**
     * @param int $id
     * @return Client|null
     */
    public function findActive($id) {
        $client = $this->find($id);
        return $client && !$client->revoked ? $client : null;
    }
    /**
     * @param mixed $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function forUser($userId) {
        return Client::where('user_id', $userId)->orderBy('name', 'desc')->get();
    }
    /**
     * @param mixed $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function activeForUser($userId) {
        return $this->forUser($userId)->reject(function ($client) {
            return $client->revoked;
        })->values();
    }
    /**
     * @return \Notadd\Foundation\Passport\Client
     */
    public function personalAccessClient() {
        if(Passport::$personalAccessClient) {
            return Client::find(Passport::$personalAccessClient);
        } else {
            return PersonalAccessClient::orderBy('id', 'desc')->first()->client;
        }
    }
    /**
     * @param $userId
     * @param $name
     * @param $redirect
     * @param bool $personalAccess
     * @param bool $password
     * @return \Notadd\Foundation\Passport\Client
     */
    public function create($userId, $name, $redirect, $personalAccess = false, $password = false) {
        $client = (new Client)->forceFill([
            'user_id' => $userId,
            'name' => $name,
            'secret' => str_random(40),
            'redirect' => $redirect,
            'personal_access_client' => $personalAccess,
            'password_client' => $password,
            'revoked' => false,
        ]);
        $client->save();
        return $client;
    }
    /**
     * @param $userId
     * @param $name
     * @param $redirect
     * @return \Notadd\Foundation\Passport\Client
     */
    public function createPersonalAccessClient($userId, $name, $redirect) {
        return $this->create($userId, $name, $redirect, true);
    }
    /**
     * @param $userId
     * @param $name
     * @param $redirect
     * @return \Notadd\Foundation\Passport\Client
     */
    public function createPasswordGrantClient($userId, $name, $redirect) {
        return $this->create($userId, $name, $redirect, false, true);
    }
    /**
     * @param \Notadd\Foundation\Passport\Client $client
     * @param $name
     * @param $redirect
     * @return \Notadd\Foundation\Passport\Client
     */
    public function update(Client $client, $name, $redirect) {
        $client->forceFill([
            'name' => $name,
            'redirect' => $redirect,
        ])->save();
        return $client;
    }
    /**\
     * @param \Notadd\Foundation\Passport\Client $client
     * @return \Notadd\Foundation\Passport\Client
     */
    public function regenerateSecret(Client $client) {
        $client->forceFill([
            'secret' => str_random(40),
        ])->save();
        return $client;
    }
    /**
     * @param $id
     * @return bool
     */
    public function revoked($id) {
        return Client::where('id', $id)->where('revoked', true)->exists();
    }
    /**
     * @param \Notadd\Foundation\Passport\Client $client
     */
    public function delete(Client $client) {
        $client->tokens()->update(['revoked' => true]);
        $client->forceFill(['revoked' => true])->save();
    }
}