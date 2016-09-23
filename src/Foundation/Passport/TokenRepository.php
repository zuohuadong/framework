<?php
/**
 * This file is part of Notadd.
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-23 17:39
 */
namespace Notadd\Foundation\Passport;
/**
 * Class TokenRepository
 * @package Notadd\Foundation\Passport
 */
class TokenRepository {
    /**
     * @param $id
     * @return \Notadd\Foundation\Passport\Token
     */
    public function find($id) {
        return Token::find($id);
    }
    /**
     * @param \Notadd\Foundation\Passport\Token $token
     * @return void
     */
    public function save($token) {
        $token->save();
    }
    /**
     * @param mixed $clientId
     * @param mixed $userId
     * @param bool $prune
     * @return void
     */
    public function revokeOtherAccessTokens($clientId, $userId, $except = null, $prune = false) {
        $query = Token::where('user_id', $userId)->where('client_id', $clientId);
        if($except) {
            $query->where('id', '<>', $except);
        }
        if($prune) {
            $query->delete();
        } else {
            $query->update(['revoked' => true]);
        }
    }
}